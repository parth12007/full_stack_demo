import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";

export const jsonServerApi = createApi({
  reducerPath: "jsonServerApi",
  baseQuery: fetchBaseQuery({
    baseUrl: "http://localhost:8080/",
  }),
  tagTypes: ["Albums"],

  endpoints: (builder) => ({
    getAlbums: builder.query({
      query: (page = 0) => `api/crud/lists?page=${page}&limit=5`,
      providesTags: ["Albums"],
    }),

    createAlbum: builder.mutation({
      query: (albumData) => ({
        url: `api/crud/add`,
        method: "POST",
        body: albumData,
      }),
      invalidatesTags: ["Albums"],
    }),

    deleteAlbum: builder.mutation({
      query: (id) => ({
        url: `api/crud/delete/${id}`,
        method: "DELETE",
      }),
      invalidatesTags: ["Albums"],
    }),

    getAlbumById: builder.query({
      query: (id) => `api/crud/get/${id}`,
    }),

    updateAlbum: builder.mutation({
      query: ({ id, albumData }) => ({
        url: `api/crud/update/${id}`,
        method: "PUT",
        body: albumData,
      }),
      invalidatesTags: ["Albums"],
    }),
  }),
});

export const {
  useGetAlbumsQuery,
  useCreateAlbumMutation,
  useDeleteAlbumMutation,
  useGetAlbumByIdQuery,
  useUpdateAlbumMutation,
} = jsonServerApi;
