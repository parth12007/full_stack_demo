import React, { useEffect, useState } from "react";
import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import TableCell from "@mui/material/TableCell";
import TableHead from "@mui/material/TableHead";
import TableRow from "@mui/material/TableRow";
import DeleteIcon from "@mui/icons-material/Delete";
import EditIcon from "@mui/icons-material/Edit";
import {
  useGetAlbumsQuery,
  useDeleteAlbumMutation,
} from "./app/services/jsonServerApi";
import Dialog from "@mui/material/Dialog";
import DialogActions from "@mui/material/DialogActions";
import DialogContent from "@mui/material/DialogContent";
import DialogContentText from "@mui/material/DialogContentText";
import DialogTitle from "@mui/material/DialogTitle";
import Button from "@mui/material/Button";
import { Link } from "react-router-dom";
import Alert from "@mui/material/Alert";
import { useLocation, useNavigate } from "react-router-dom";

export default function TableDemo() {
  const [open, setOpen] = useState(false);
  const [albumToDelete, setAlbumToDelete] = useState(null);
  const [deleteAlbum] = useDeleteAlbumMutation();
  const [message, setMessage] = useState(false);
  const { state } = useLocation();
  const Navigate = useNavigate();
  const [page, setPage] = useState(0);
  const { data: albums = [] } = useGetAlbumsQuery(page);
  const [successMessage, setSuccessMessage] = useState(state);
  const recordsPerPage = 5;

  const totalPages = Math.ceil(albums?.count / recordsPerPage);
  const isPreviousDisabled = page <= 0;
  const isNextDisabled = page >= totalPages - 1 || !albums?.data?.length;

  const handleDeleteClick = (album) => {
    setAlbumToDelete(album);
    setOpen(true);
  };

  const handleDeleteConfirm = () => {
    if (albumToDelete) {
      deleteAlbum(albumToDelete.id)
        .then((response) => {
          if (response.data.Status === "Success") {
            setMessage(true);
            setTimeout(() => {
              setMessage(false);
            }, 2000);
          }
        })
        .catch((error) => {
          console.error("Error deleting album:", error);
        });
    }
    setOpen(false);
  };

  const handleDeleteCancel = () => {
    setAlbumToDelete(null);
    setOpen(false);
  };

  useEffect(() => {
    setTimeout(() => {
      setSuccessMessage("");
    }, 3000);
    Navigate("/", { state: "" });
  }, [Navigate]);

  const handlePreviousClick = () => {
    if (!isPreviousDisabled) {
      setPage((prev) => prev - 1);
    }
  };

  const handleNextClick = () => {
    if (!isNextDisabled) {
      setPage((prev) => prev + 1);
    }
  };

  return (
    <div className="center-container table">
      {successMessage && <Alert severity="success">{successMessage}</Alert>}
      {message && <Alert severity="success">Record Deleted Successfully</Alert>}
      <Table>
        <TableHead className="head">
          <TableRow>
            <TableCell>Client Id</TableCell>
            <TableCell>Name</TableCell>
            <TableCell>Email</TableCell>
            <TableCell>Phone No</TableCell>
            <TableCell>Comment</TableCell>
            <TableCell>Edit</TableCell>
            <TableCell>Delete</TableCell>
          </TableRow>
        </TableHead>
        {albums?.data?.map((album) => (
          <TableBody sx={{ margin: 10 }}>
            <TableRow key={album.id}>
              <TableCell>{album.client_id}</TableCell>
              <TableCell>{album.name}</TableCell>
              <TableCell>{album.email}</TableCell>
              <TableCell>{album.phone_number}</TableCell>
              <TableCell>{album.comment}</TableCell>
              <TableCell>
                <Link to={`/edit/${album.id}`}>
                  <EditIcon />
                </Link>
              </TableCell>
              <TableCell>
                <DeleteIcon
                  onClick={() => handleDeleteClick(album)}
                  className="delete"
                />
              </TableCell>
            </TableRow>
          </TableBody>
        ))}
      </Table>
      <div className="page-btn">
        <Button
          variant="contained"
          className="previous-btn"
          disabled={isPreviousDisabled}
          onClick={handlePreviousClick}
        >
          Previous
        </Button>
        <div className="page">{page}</div>
        <Button
          variant="contained"
          disabled={isNextDisabled}
          onClick={handleNextClick}
        >
          Next
        </Button>
      </div>
      <Dialog open={open} onClose={handleDeleteCancel}>
        <DialogTitle>Confirm Deletion</DialogTitle>
        <DialogContent>
          <DialogContentText>
            Are you sure you want to delete this record?
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleDeleteCancel} color="primary">
            Cancel
          </Button>
          <Button onClick={handleDeleteConfirm} color="primary">
            Confirm
          </Button>
        </DialogActions>
      </Dialog>
    </div>
  );
}
