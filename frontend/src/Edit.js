import React from "react";
import { TextField, Button } from "@mui/material";
import { Formik, Form, ErrorMessage, Field } from "formik";
import { useNavigate, useParams } from "react-router-dom";
import {
  useGetAlbumByIdQuery,
  useUpdateAlbumMutation,
} from "./app/services/jsonServerApi";

const Edit = () => {
  const Navigate = useNavigate();
  const { id } = useParams();
  const { data: album } = useGetAlbumByIdQuery(id);
  const [updateAlbum, { isLoading }] = useUpdateAlbumMutation();
  const initialFormValues = {
    name: album?.Data?.name || "",
    email: album?.Data?.email || "",
    number: album?.Data?.phone_number || "",
    comment: album?.Data?.comment || "",
  };
  console.log(initialFormValues)

  const handleSubmit = (values, { setSubmitting }) => {
    const formData = {
      request_data: {
        name: values.name,
        phone_number: values.number,
        email: values.email,
        comment: values.comment,
      },
    };

    updateAlbum({ id, albumData: formData })
      .then((response) => {
        if (response.data.Status === "Success") {
          Navigate("/", { state: "Record Edited Successfully!" });
        } else {
          Navigate("/", { state: "" });
        }
      })
      .catch((error) => {
        console.error("Error creating album:", error);
      })
      .finally(() => {
        setSubmitting(false);
      });
  };
  if (!album) {
    return <div>Loading album data...</div>;
  }
  
  if (isLoading) {
    return <div>Loading...</div>;
  }
  return (
    <div className="center-container">
      <Formik
        initialValues={initialFormValues} // Updated this line
        validate={(values) => {
          const errors = {};

          if (!values.name) {
            errors.name = "Name is required";
          }

          if (!values.email) {
            errors.email = "Email is required";
          } else if (
            !/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i.test(values.email)
          ) {
            errors.email = "Invalid email address";
          }

          if (values.number !== "") {
            if (!/^\d{10}$/.test(values.number)) {
              errors.number = "Mobile No must be a 10-digit number";
            }
          }

          if (!values.comment) {
            errors.comment = "Comment is required";
          } else if (values.comment.length > 1000) {
            errors.comment = "Comment must be 1000 characters or less";
          }
          return errors;
        }}
        onSubmit={handleSubmit}
      >
        <div>
          <Form autoComplete="off" className="form" noValidate>
            <h2>Edit Record</h2>
            <div>
              <label htmlFor="name" style={{ marginBottom: "10px" }}>
                Enter Name
              </label>
              <div>
                <Field
                  as={TextField}
                  name="name"
                  id="name"
                  variant="outlined"
                  color="secondary"
                  type="text"
                  fullWidth
                  sx={{ mb: 3 }}
                />
              </div>
              <ErrorMessage name="name" component="div" className="error" />
            </div>

            <div>
              <label htmlFor="email">Enter Email</label>
              <Field
                as={TextField}
                name="email"
                variant="outlined"
                color="secondary"
                type="text"
                fullWidth
                sx={{ mb: 3 }}
              />
              <ErrorMessage name="email" component="div" className="error" />
            </div>

            <div>
              <label htmlFor="number">Enter Mobile No</label>
              <Field
                as={TextField}
                name="number"
                variant="outlined"
                color="secondary"
                fullWidth
                sx={{ mb: 3 }}
              />
            </div>
            <ErrorMessage name="number" component="div" className="error" />
            <div>
              <label htmlFor="comment">Enter Comment</label>
              <Field
                as={TextField}
                name="comment"
                multiline
                rows={2}
                maxRows={4}
                fullWidth
                sx={{ mb: 3 }}
              />
              <ErrorMessage name="comment" component="div" className="error" />
            </div>

            <Button
              variant="outlined"
              color="secondary"
              type="submit"
              className="btn"
            >
              Update
            </Button>
          </Form>
        </div>
      </Formik>
    </div>
  );
};

export default Edit;
