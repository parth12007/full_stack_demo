# Full Stack Demo
Two technology used in this project Lumen (8.0) and React JS (18.2.0).

## Requirement
- PHP: 7.3|8.0
- node version: 18
- npm version: 9

# Installation
Clone the git repository
```bash
git clone https://github.com/parth12007/full_stack_demo
```
Go Project director
```
cd full_stack_demo
```
Install the back-end dependencies and devDependencies and start the server.
```sh
cd backend
composer install
php -S localhost:8080 -t public/
```

Install the front-end dependencies and devDependencies and start the server.

```sh
cd frontend
npm install --legacy-peer-deps
node start
```
> Note: `--legacy-peer-deps` is required for all peerDependencies when installing, in the style of npm version of Material ui.

## How to setup backend API 
```sh
cp .env.example .env
```
Go into .env file and change Database and testing databases.
Go into .env.testing file and change Testing Database.

* `php artisan migrate` - run all migration.

* `php artisan migrate --database=testing` - run all testing migration.

Verify the deployment by navigating to your server address in
your preferred browser.

```sh
127.0.0.1:3000
```

## Running tests

The project comes with some API level tests implemented using PHPUnit.
> Note: Before the execute below command, directory path need to backend.
* `vendor/bin/phpunit` - run all tests

## GitHub URL

* https://github.com/parth12007/full_stack_demo


# Client Question and Answer

## 2. Check the pull request
   You have a Pull Request to review! \
   Documentation states the following:
   - ENDPOINT: https://api.nordiceasy.no/register METHOD: POST
   - PARAMS:
     - client_id: aabbcc
     - email: your@email.address
     - name: Your Name

   RETURNS:
   - sl_token: This token string should be used in the subsequent query. Please note that this token will only last 1 hour
   from when the REGISTER call happens. You will need to register and fetch a new token when you need it.
   - client_id: returned for informational purposes only
   - email: returned for informational purposes only

   You receive a pull request with the following line of code. Please review the code.

```<?php
$tokenInfo = file_get_contents('https://api.nordiceasy.noregister?client_id=aabbcc&email=my@name.com&name=My%20Name');
```
## 2. Answer
I reviewed pull request and below are my findings and suggestions.


```
1) Instead of file_get_contents use cURL call with POST request.

<?php
$endpoint = 'https://api.nordiceasy.no/register';
$data = array(
    'client_id' => 'aabbcc',
    'email' => 'my@name.com',
    'name' => 'My Name'
);

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    // Handle error here
    echo 'Error: ' . curl_error($ch);
} else {
    // Process the response
    $result = json_decode($response, true);
    $sl_token = $result['sl_token'];
    $client_id = $result['client_id'];
    $email = $result['email'];

    // Use sl_token for subsequent queries
}

curl_close($ch);
```

    2) Endpoint method is POST and you are passing parameter as a query string where as these parameter should be passed as post parameter. Check above curl call

 
```
3) There is no response status mentioned. Return value should be as per below

For example:

json
{
    'status' : true,
    'result' : {
        'sl_token' : ,
        'client_id'
    }
}
```


## 3. Legacy code refactoring
   Refactor the following piece of legacy code\
   You do not need to write code, you may instead describe all the problems you see and how you would refactor this
   legacy piece of code. Bonus for thinking in an object-oriented way. Of course you may also submit stubs of the code
   refactored if you have time.

* Please note this is not at all code taken from Nordic Easy.

```<?php
if ($_REQUEST['email']) {
    $masterEmail = $_REQUEST['email'];
}

$masterEmail = isset($masterEmail) && $masterEmail ? $masterEmail : array_key_exists('masterEmail', $_REQUEST) && $_REQUEST["masterEmail"] ? $_REQUEST['masterEmail'] : 'unknown';

echo 'The master email is ' . $masterEmail . '\n';
$conn = mysqli_connect('localhost', 'root', 'sldjfpoweifns', 'my_database');
$res = mysqli_query($conn, "SELECT * FROM users WHERE email='" . $masterEmail . "'"); $row = mysqli_fetch_row($res);
echo $row['username'] . "\n";
```

## 3. Answer
	Lack of Error Handling:
		If the database connection or query fails, it will result in a fatal error. You can handle it with try-catch

		Refactoring:
			Wrap database operations in try-catch blocks to handle exceptions gracefully.
			Consider using prepared statements to prevent SQL injection.

	Mixing Input and Output Logic:
		The code mixes input processing ($_REQUEST) with output (echo) logic, making it less modular and harder to maintain.
		Separate input processing from output logic.
		Use a more structured approach to handle input data.
		Redundant Assignment:
			There are redundant assignments of the $masterEmail variable.
	Use of Global Variables:
		The code directly accesses global $_REQUEST variables.
		Refactoring:
			Encapsulate the request handling in a function or a class method, passing input parameters as arguments.


	Array Key Error:
		There is a missing check for the existence of the 'username' key in the fetched row.
		Refactoring:
			Check if the 'username' key exists in the $row array before trying to echo it.

Below is refactored code.

```<?php
	class UserManager {
	    private $db;

	    public function __construct(mysqli $db) {
	        $this->db = $db;
	    }

	    public function getMasterEmail($request) {
	        if (isset($request['email'])) {
	            return $request['email'];
	        } elseif (isset($request['masterEmail'])) {
	            return $request['masterEmail'];
	        } else {
	            return 'unknown';
	        }
	    }

	    public function fetchUsernameByEmail($masterEmail) {
	        try {
	            $query = "SELECT * FROM users WHERE email=?";
	            $stmt = $this->db->prepare($query);
	            $stmt->bind_param("s", $masterEmail);
	            $stmt->execute();
	            $result = $stmt->get_result();

	            if ($result->num_rows === 0) {
	                return 'User not found';
	            }

	            $row = $result->fetch_assoc();
	            return $row['username'];
	        } catch (Exception $e) {
	            return 'Error: ' . $e->getMessage();
	        }
	    }
	}

	// Usage
	$request = $_REQUEST;
	$db = new mysqli('localhost', 'root', 'sasas', 'my_database');
	$userManager = new UserManager($db);
	$masterEmail = $userManager->getMasterEmail($request);
	echo 'The master email is ' . $masterEmail . '\n';
	$username = $userManager->fetchUsernameByEmail($masterEmail);
	echo $username . "\n";
```

## 4. Open source libraries
Would you use a class/library provided by an external framework in your code, why or why not?

## 4. Answer
	Yes, we should use open source library in our code. It has good below benefit to use it in our code.
		1) Librady is well documented and maintained.
		2) It is tested properly by author and team.
		3) It reduced our time because if we build it from scratch, it will take many ours to develop and testing and bug fixing.

	Considering above point, We should use open source libraries to reduced development time and cost.

	But before using it, we should take care below few points then only we can use that open source library.
		1) Open source library must have MIT license.
		2) It must support LTS (Long term support for security fixes)
		3) There must be most downloaded and should have proper community page so that we can ask any question if we have.
		4) Must have proper README.txt which include installation steps and couple of practical examples.