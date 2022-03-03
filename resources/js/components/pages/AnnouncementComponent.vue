<template>
    <div class="manage-table">
        
        <div class="tab announcement-tab">
            <h4> Manage <b>Announcement</b></h4>

            <div class="tab-action">
                <b-button type="button" @click="showModal(false, 'Add', 0)" class="btn btn-add"> <i class="fa-solid fa-plus"></i> Post</b-button>

                <input type="text" v-model="keyword">
                <select v-model="filterBy">
                    <option value="title">Title</option>
                    <option value="author">Author</option>
                    <option value="date">Date</option>
                    <option value="category">Category</option>
                </select>
            </div>
        </div>

        <b-table
            id="doctor-table"
            outlined
            :fields="fields" 
            :items="items" 
            :per-page="perPage"
            :current-page="currentPage"
        >
        
        <template #cell(action)="data">
            <button class="btn bg-warning" @click="showModal(false, 'Update', data.item.id)"><i class="fa-solid fa-pencil"></i></button>
            <button class="btn bg-danger" @click="deleteAnnouncement(data.item.id, data.item.photoUrl, data.item.title)"><i class="fa-solid fa-trash"></i></button>
        </template>


        </b-table>
    </div>
</template>

<script>
    export default {
        props:['announcementData'],

        data() {
            return { 
                
                perPage: 5,
                currentPage: 1,
                
                keyword: '',
                filterBy: 'title',
                announcements: this.announcementData,
                
                fields: [
                    {
                        key: 'title', 
                        label: 'Title', 
                        sortable: true,
                        tdClass: 'text-hidden-ellipsis',
                    },

                    {
                        key: 'author', 
                        label: 'Author', 
                        sortable: true,
                        tdClass: 'text-hidden-ellipsis',
                    },

                    {
                        key: 'date', 
                        label: 'Date', 
                        sortable: true,
                        tdClass: 'text-hidden-ellipsis',
                    },

                    {
                        key: 'category', 
                        label: 'Category', 
                        sortable: true,
                        tdClass: 'text-hidden-ellipsis',
                    },
                    
                    {
                        key: 'action', 
                        label: 'Action', 
                    },
                ],
            }
        },

        methods:{
            showModal(isDoctor, operation, id){
                this.$parent.showModal(isDoctor, operation, id);
            },

            addAnnouncement(announceForm){
               const filePhoto = new FormData();
                filePhoto.append('title', announceForm.title)
                filePhoto.append('body', announceForm.body)
                filePhoto.append('category', announceForm.category)
                filePhoto.append('photo', announceForm.photoUrl)
    

                return axios.post('/announce/add/', filePhoto)
                .then( function (response){
                    return response.data
                })
                .catch( function (error){
                    console.log(error);
                });
            },

            deleteAnnouncement(id, photoUrl, title){
                if( confirm("Do you really want to delete? " + title) ){
                    let self = this 
                    axios.post('/announce/delete/', {
                            id: id,
                            photoUrl: photoUrl, 
                    })
                    .then( function (response){
                        if(response.data.success){
                            self.$parent.successDeleteAnnounce( response.data.success, id)
                        }
                    })
                    .catch( function (error){
                        console.log(error);
                    });
                }

            },

            updateAnnouncement(announceForm){

                const filePhoto = new FormData();
                filePhoto.append('title', announceForm.title)
                filePhoto.append('body', announceForm.body)
                filePhoto.append('category', announceForm.category)
                filePhoto.append('photo', announceForm.photoUrl)
                filePhoto.append('id', announceForm.id)
                filePhoto.append('oldPhoto', announceForm.oldPhoto)

                return axios.post('/announce/update/', filePhoto)
                .then( function (response){
                    return response.data
                })
                .catch( function (error){
                    console.log(error);
                });
            },
        },

        computed:{
            items(){
                return this.keyword
                    ? this.announcements.filter(
                        item => item[this.filterBy].toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.announcements
            },

            rows() {
                return this.items.length
            },
        },

        // mounted(){
        //     console.log(this.announcements)
        // }

    }
</script>