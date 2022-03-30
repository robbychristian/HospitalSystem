<template>
    <div class="manage-table">
        <div class="tab announcement-tab my-3">
            <!--ADD ANNOUNCEMENT MODAL-->
            <b-modal
                id="addAnnouncement"
                centered
                title="Add Announcement"
                ref="addAnnouncement"
                @ok="addAnnouncement"
            >
                <form action="announcement/add" method="post" ref="form">
                    <input type="hidden" name="_token" v-bind:value="csrf" />
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Title: </label></b-col>
                        <b-col sm="9">
                            <b-form-input
                                v-model="title"
                                name="title"
                            ></b-form-input>
                        </b-col>
                    </b-row>
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Category: </label></b-col>
                        <b-col sm="9">
                            <b-form-input
                                v-model="category"
                                name="category"
                            ></b-form-input>
                        </b-col>
                    </b-row>
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Description: </label></b-col>
                        <b-col sm="9">
                            <b-form-textarea
                                id="textarea"
                                style="overflow-y: hidden"
                                v-model="body"
                                rows="3"
                                name="body"
                                max-rows="5"
                            ></b-form-textarea>
                        </b-col>
                    </b-row>
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Photo: </label></b-col>
                        <b-col sm="9">
                            <input
                                type="file"
                                accept=".png, .jpg, .jpeg"
                                class="form-control-file"
                                id="exampleFormControlFile1"
                                ref="photoUrl"
                                @change="announcementPhoto"
                            />
                        </b-col>
                    </b-row>
                </form>
            </b-modal>

            <!--UPDATE ANNOUNCEMENT MODAL-->
            <b-modal
                id="updateAnnouncement"
                centered
                title="Update Announcement"
                ref="updateAnnouncement"
                @ok="updateAnnouncement(itemId, oldPhotoUrl)"
            >
                <form action="announcement/add" method="post" ref="form">
                    <input type="hidden" name="_token" v-bind:value="csrf" />
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Title: </label></b-col>
                        <b-col sm="9">
                            <b-form-input
                                v-model="title"
                                name="title"
                            ></b-form-input>
                        </b-col>
                    </b-row>
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Category: </label></b-col>
                        <b-col sm="9">
                            <b-form-input
                                v-model="category"
                                name="category"
                            ></b-form-input>
                        </b-col>
                    </b-row>
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Description: </label></b-col>
                        <b-col sm="9">
                            <b-form-textarea
                                id="textarea"
                                style="overflow-y: hidden"
                                v-model="body"
                                rows="3"
                                name="body"
                                max-rows="5"
                            ></b-form-textarea>
                        </b-col>
                    </b-row>
                    <b-row class="mb-2">
                        <b-col sm="3"><label>Photo: </label></b-col>
                        <b-col sm="9">
                            <input
                                type="file"
                                accept=".png, .jpg, .jpeg"
                                class="form-control-file"
                                id="exampleFormControlFile1"
                                ref="photoUrl"
                                @change="announcementPhoto"
                            />
                        </b-col>
                    </b-row>
                </form>
            </b-modal>

            <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                aria-controls="my-table"
            ></b-pagination>
            <!--TABLE HEADER-->
            <div class="row mx-2">
                <div class="col-lg-6 col-xl-6 col-md-7 col-sm-8">
                    <div class="row">
                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3">
                            <div class="h4 font-weight-bold mb-2">Filter</div>
                        </div>
                        <div class="col">
                            <input type="text" v-model="keyword" />
                            <select v-model="filterBy">
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="date">Date</option>
                                <option value="category">Category</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-end">
                    <b-button
                        type="button"
                        @click="addModal"
                        class="btn"
                        style="background-color: #48898c"
                    >
                        <i class="fa-solid fa-plus"></i> Add
                        Announcement</b-button
                    >
                </div>
            </div>
        </div>
        <!--TABLE CONTENT-->
        <b-table
            id="doctor-table"
            outlined
            :fields="fields"
            :items="items"
            :per-page="perPage"
            :current-page="currentPage"
        >
            <template #cell(action)="data">
                <button
                    class="btn bg-warning"
                    @click="updateModal(data.item.id, data.item.photoUrl)"
                >
                    <i class="fa-solid fa-pencil"></i>
                </button>
                <button
                    class="btn bg-danger"
                    @click="
                        deleteAnnouncement(
                            data.item.id,
                            data.item.photoUrl,
                            data.item.title
                        )
                    "
                >
                    <i class="fa-solid fa-trash"></i>
                </button>
            </template>
        </b-table>
    </div>
</template>

<script>
import axios from "axios";
import swal from "sweetalert";

export default {
    props: ["announcementData", "csrf", "userData"],

    mounted() {
        console.log(JSON.parse(this.userData));
        console.log(JSON.parse(this.announcementData));
    },

    data() {
        return {
            // PAGIANATION
            perPage: 10,
            currentPage: 1,

            //add announcement form
            title: "",
            body: "",
            category: "",
            photo: null,

            //update announcement
            itemId: "",
            oldPhotoUrl: null,

            //user data
            user: JSON.parse(this.userData),

            //pagination
            perPage: 5,
            currentPage: 1,

            //filter
            keyword: "",
            filterBy: "title",

            announcements: JSON.parse(this.announcementData),

            fields: [
                {
                    key: "title",
                    label: "Title",
                    sortable: true,
                    tdClass: "text-hidden-ellipsis",
                },

                {
                    key: "author",
                    label: "Author",
                    sortable: true,
                    tdClass: "text-hidden-ellipsis",
                },

                {
                    key: "date",
                    label: "Date",
                    sortable: true,
                    tdClass: "text-hidden-ellipsis",
                },

                {
                    key: "category",
                    label: "Category",
                    sortable: true,
                    tdClass: "text-hidden-ellipsis",
                },

                {
                    key: "action",
                    label: "Action",
                },
            ],
        };
    },

    methods: {
        addModal() {
            this.$refs["addAnnouncement"].show();
        },

        updateModal(itemId, oldPhotoUrl) {
            this.itemId = itemId;
            //console.log(this.itemId);
            this.oldPhotoUrl = oldPhotoUrl;
            //console.log(this.oldPhotoUrl);
            this.$refs["updateAnnouncement"].show();
        },

        announcementPhoto(e) {
            console.log(e.target.files[0]);
            this.photo = e.target.files[0];
        },

        addAnnouncement() {
            if (this.title == "" || this.category == "" || this.body == "") {
                swal({
                    title: "Error",
                    text: "Some fields are required to be filled!",
                    icon: "warning",
                    button: {
                        text: "OK",
                    },
                });
            } else {
                const filePhoto = new FormData();
                filePhoto.append("title", this.title);
                filePhoto.append("category", this.category);
                filePhoto.append("body", this.body);
                filePhoto.append("photo", this.photo);
                filePhoto.append("drId", this.user.id_fb);
                filePhoto.append("name", this.user.name);
                filePhoto.append("photoUrl", this.user.photoUrl);

                axios
                    .post("api/announcement/add", filePhoto)
                    .then((response) => {
                        swal(
                            "Posted!",
                            "The announcemet has been posted!",
                            "success",
                            {
                                button: false,
                            }
                        );
                    })
                    .then((response) => {
                        window.location.reload();
                    })
                    .catch((e) => {
                        console.log(e);
                    });
            }
        },

        deleteAnnouncement(id, photoUrl, title) {
            swal("Do you really want to delete?", {
                icon: "warning",
                buttons: {
                    cancel: "No",
                    Yes: true,
                },
            }).then((value) => {
                if (value) {
                    axios
                        .post("/api/announcement/delete", {
                            id: id,
                            photoUrl: photoUrl,
                        })
                        .then((response) => {
                            swal(
                                "Deleted!",
                                "The announcement has been deleted!",
                                "success",
                                {
                                    buttons: false,
                                }
                            );
                        })
                        .then((response) => {
                            window.location.reload();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else {
                    swal("Canceled!", "Announcement not deleted!", "error");
                }
            });
        },

        updateAnnouncement(itemId, oldPhotoUrl) {
            if (this.title == "" || this.body == "" || this.category == "") {
                swal({
                    title: "Error",
                    text: "Some fields are required to be filled!",
                    icon: "warning",
                    button: {
                        text: "OK",
                    },
                });
            } else {
                const filePhoto = new FormData();
                filePhoto.append("title", this.title);
                filePhoto.append("body", this.body);
                filePhoto.append("category", this.category);
                filePhoto.append("photo", this.photo);
                filePhoto.append("id", itemId);
                filePhoto.append("oldPhotoUrl", oldPhotoUrl);

                axios
                    .post("/api/announcement/update", filePhoto)
                    .then((response) => {
                        console.log(response.data);
                        swal(
                            "Updated!",
                            "The announcemet has been updated!",
                            "success",
                            {
                                buttons: false,
                            }
                        );
                    })
                    .then((response) => {
                        window.location.reload();
                    });
            }
        },
    },

    computed: {
        items() {
            return this.keyword
                ? this.announcements.filter((item) =>
                      item[this.filterBy]
                          .toLowerCase()
                          .includes(this.keyword.toLowerCase())
                  )
                : this.announcements;
        },

        rows() {
            return this.items.length;
        },
    },

    // mounted(){
    //     console.log(this.announcements)
    // }
};
</script>
