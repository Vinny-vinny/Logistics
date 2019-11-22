<template>
   <div>
       <track-by v-if="add_track" :edit="editing"></track-by>
        <!-- Main content -->
       <section class="content" v-if="!add_track">
           <!-- Default box -->
           <div class="box">
               <div class="box-header with-border">
                   <h3 class="box-title">Track By</h3>
                   <button class="btn btn-primary pull-right" @click="add_track=true">Add Track By</button>
               </div>
               <div class="box-body">
                   <table class="table table-striped dt">
                       <thead>
                       <tr>
                           <th>#</th>
                           <th>Name</th>
                           <th style="display: none">Name</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                       <tbody>
                       <tr v-for="track in tableData">
                           <td>{{track.id}}</td>
                           <td>{{track.name}}</td>
                           <td style="display: none">{{track.name}}</td>
                           <td>
                               <button class="btn btn-success btn-sm" @click="editTrack(track)"><i class="fa fa-edit"></i></button>
                               <button class="btn btn-danger btn-sm" @click="deleteTrack(track.id)"><i class="fa fa-trash"></i></button>
                           </td>
                       </tr>
                       </tbody>
                   </table>
               </div>
               </div>
           </section>
   </div>
</template>
<script>
    import TrackBy from "./TrackBy";
    export default {
        data(){
            return {
                tableData: [],
                add_track: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getTracks();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getTracks(){
                axios.get('track-by')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editTrack(track){
                this.$store.dispatch('updateTrack',track)
                    .then(() =>{
                        this.editing=true;
                        this.add_track=true;
                    })
            },
            deleteTrack(id){
                axios.delete(`track-by/${id}`)
                    .then(res => {
                             for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            listen(){
                eventBus.$on('listTracks',(track) =>{
                    this.tableData.unshift(track);
                    this.add_track =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_track = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateTrack',(track)=>{
                    this.add_track = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == track.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(track);
                    this.initDatable();
                });
                },
            initDatable(){
                setTimeout(()=>{
                    $('.dt').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        order: [[ 0, 'asc' ], [ 3, 'desc' ]],
                        responsive: true,
                        destroy: true,
                        retrieve:true,
                        autoFill: true,
                        colReorder: true,

                    });
                },1000)
            },
        },
        components:{
            TrackBy
        }
    }
</script>

<style scoped>

</style>
