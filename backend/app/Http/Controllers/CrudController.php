<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use App\Services\ValidationService;
use App\Repositories\CrudRepository;

class CrudController extends Controller
{


    private $validationService;

    public function __construct(ValidationService $validationService, CrudRepository $crudRepository)
    {
        $this->validationService    =   $validationService;
        $this->crudRepository    =   $crudRepository;
    }

    /**
     * Get comments lists.
     *
     * @Method({"GET"})
     *
     * @Route("/api/crud/lists",
     *      name = "comments-lists")
     *
     *
     * @Response(
     *  description = "Get comments lists",
     *  section = "Comments",
     *  statusCodes = {
     *    200 = "OK",
     *    422 = "Invalid parameters",
     *    404 = "Comment Id does not exist",
     *    500 = "Server side Exception"
     *   }
     *  )
     */
    public function lists()
    {
        try {
            $requestQueryData    =   request()->query();

            $page = (!empty($requestQueryData['page'])) ? $requestQueryData['page'] : 0;
            $limit = (!empty($requestQueryData['limit'])) ? $requestQueryData['limit'] : 10;
            $offset = $page * $limit;

            $comments = Comment::limit($limit)->offset($offset)->get()->toArray();
            $count = Comment::count();
            $msg = 'All comments lists.';
            $response['data'] = $comments;
            $response['count'] = $count;
            return $this->jsonResponse(
                $msg,
                $response,
            );
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            return $this->jsonResponse(
                $msg,
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Add comment.
     *
     * @Method({"POST"})
     *
     * @Route("/api/crud/add",
     *      name = "comments-add")
     *
     *
     * @Response(
     *  description = "Add comment",
     *  section = "Comments",
     *  statusCodes = {
     *    200 = "OK",
     *    422 = "Invalid parameters",
     *    404 = "Comment Id does not exist",
     *    500 = "Server side Exception"
     *   }
     *  )
     */
    public function add()
    {
        try {
            Log::info("CommentAdd : Save comment in db.");

            $this->validate(request(), [
                'request_data' => 'required|array'
            ]);

            $requestData    =   request()->request_data;

            $validationError = $this->validationService->validateRequiredFields($requestData);

            //Mandatory validation fields
            if (!empty($validationError)) {
                return $this->jsonResponse(
                    'The request doesn\'t match schema',
                    ['Errors' => $validationError],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $data = $this->crudRepository->saveDataToComment($requestData);

            if ($data) {
                return $this->jsonResponse(
                    'Comment save Successfully in DB.',
                    ['Data' => $data],
                    Response::HTTP_OK
                );
            } else {
                return $this->jsonResponse(
                    'Comment is not save in DB.',
                    ['Errors' => $data['messages']],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            return $this->jsonResponse(
                $msg,
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Get comments details by Id.
     *
     * @Method({"GET"})
     *
     * @Route("/api/crud/get/{id}",
     *      name = "comments-lists")
     *
     *
     * @Response(
     *  description = "Get comments lists",
     *  section = "Comments",
     *  statusCodes = {
     *    200 = "OK",
     *    422 = "Invalid parameters",
     *    404 = "Comment Id does not exist",
     *    500 = "Server side Exception"
     *   }
     *  )
     */
    public function get($id)
    {
        try {
            $comment = Comment::find($id);
            if ($comment) {
                return $this->jsonResponse(
                    'Get Comment details Successfully.',
                    ['Data' => $comment],
                    Response::HTTP_OK
                );
            } else {
                return $this->jsonResponse(
                    'Comment Id does not exist.',
                    [],
                    Response::HTTP_NOT_FOUND
                );
            }
            $msg = 'All comments lists.';
            $response['data'] = $comments;
            return $this->jsonResponse(
                $msg,
                $response,
            );
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            return $this->jsonResponse(
                $msg,
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update comment.
     *
     * @Method({"POST"})
     *
     * @Route("/api/crud/update/{id}",
     *      name = "comments-update")
     *
     *
     * @Response(
     *  description = "Update comment",
     *  section = "Comments",
     *  statusCodes = {
     *    200 = "OK",
     *    422 = "Invalid parameters",
     *    404 = "Comment Id does not exist",
     *    500 = "Server side Exception"
     *   }
     *  )
     */
    public function update($id)
    {
        try {
            Log::info("CommentUpdate : Update comment in db.");

            $comment = Comment::find($id);
            if (!$comment) {
                return $this->jsonResponse(
                    'Comment Id does not exist.',
                    [],
                    Response::HTTP_NOT_FOUND
                );
            }

            $this->validate(request(), [
                'request_data' => 'required|array'
            ]);

            $requestData    =   request()->request_data;

            $validationError = $this->validationService->validateRequiredFields($requestData);

            //Mandatory validation fields
            if (!empty($validationError)) {
                return $this->jsonResponse(
                    'The request doesn\'t match schema',
                    ['Errors' => $validationError],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $data = $this->crudRepository->updateDataToComment($comment, $requestData);

            if ($data) {
                return $this->jsonResponse(
                    'Comment update Successfully in DB.',
                    [],
                    Response::HTTP_OK
                );
            } else {
                return $this->jsonResponse(
                    'Comment is not update in DB.',
                    ['Errors' => $data['messages']],
                    Response::HTTP_OK
                );
            }
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            return $this->jsonResponse(
                $msg,
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Delete comment.
     *
     * @Method({"delete"})
     *
     * @Route("/api/crud/delete/{id}",
     *      name = "comments-update")
     *
     *
     * @Response(
     *  description = "Delete comment",
     *  section = "Comments",
     *  statusCodes = {
     *    200 = "OK",
     *    422 = "Invalid parameters",
     *    404 = "Comment Id does not exist",
     *    500 = "Server side Exception"
     *   }
     *  )
     */
    public function delete($id)
    {
        try {
            Log::info("CommentUpdate : Delete comment in db.");

            $comment = Comment::find($id);
            if (!$comment) {
                return $this->jsonResponse(
                    'Comment Id does not exist.',
                    [],
                    Response::HTTP_NOT_FOUND
                );
            }

            $comment->delete();

            return $this->jsonResponse(
                'Comment deleted Successfully in DB.',
                [],
                Response::HTTP_OK
            );
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            return $this->jsonResponse(
                $msg,
                [],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}
