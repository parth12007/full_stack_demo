import "./App.css";
import TableDemo from "./Table";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Add from "./Add";
import Navbar from "./Navbar";
import Edit from "./Edit";

function App() {
  return (
    <div className="center-container table">
      <Navbar/>
      <Router>
        <Routes>
          <Route path="/" element={<TableDemo />} />
          <Route path="/add" element={<Add />} />
          <Route path="/edit/:id" element={<Edit />} />
        </Routes>
      </Router>
    </div>
  );
}

export default App;
