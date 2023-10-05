import React from "react";
import { TextField, Button } from "@mui/material";
import { Formik, Form, ErrorMessage, Field } from "formik";
import { useNavigate } from "react-router-dom";
import { useCreateAlbumMutation } from "./app/services/jsonServerApi";
import { v4 as uuidv4 } from "uuid";


const Add = () => {
  const Navigate = useNavigate();
  const [createAlbum, { isLoading }] = useCreateAlbumMutation();

  const handleSubmit = (values, { setSubmitting }) => {
    const formData = {
      request_data: {
        client_id: uuidv4(),
        name: values.name,
        phone_number: values.number,
        email: values.email,
        comment: values.comments,
      },
    };

    createAlbum(formData)
      .then((response) => {
        if (response.data.Status === "Success") {
            Navigate("/", { state:  "Record Added Successfully!" });
        }
        else
        {
          Navigate("/", {state : ""})
        }
      })
      .catch((error) => {
        console.error("Error creating album:", error);
      })
      .finally(() => {
        setSubmitting(false);
      });
  };


  if (isLoading) {
    return <div>Loading...</div>;
  }
  return (
    <div className="center-container">
      <Formik
        initialValues={{ name: "", email: "", number: "", comments: "" }}
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

          if (values.number !== ""){
            if (!/^\d{10}$/.test(values.number)){
              errors.number = "Mobile No must be a 10-digit number";
            }
          }

          if (!values.comments) {
            errors.comments = "Comment is required";
          } else if (values.comments.length > 1000) {
            errors.comments = "Comment must be 1000 characters or less";
          }
          return errors;
        }}
        onSubmit={handleSubmit}
      >
        <div>
          <Form autoComplete="off" className="form" noValidate>          
            <h2>Add Record</h2>
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
              <label htmlFor="comments">Enter Comment</label>
              <Field
                as={TextField}
                name="comments"
                multiline
                rows={2}
                maxRows={4}
                fullWidth
                sx={{ mb: 3 }}
              />
              <ErrorMessage name="comments" component="div" className="error" />
            </div>

            <Button
              variant="outlined"
              color="secondary"
              type="submit"
              className="btn"
            >
              Add
            </Button>
          </Form>
        </div>
      </Formik>
    </div>
  );
};

export default Add;
