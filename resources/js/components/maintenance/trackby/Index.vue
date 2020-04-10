<template>
   <div>
       <track-by v-if="add_track" :edit="editing"></track-by>
        <!-- Main content -->
       <section class="content" v-if="!add_track">
           <!-- Default box -->
           <div class="box">
               <div class="box-body">
                   <v-app id="inspire">
                       <v-card>
                           <v-card-title>
                               Track  By
                               <v-spacer></v-spacer>
                               <v-text-field
                                   v-model="search"
                                   append-icon="mdi-magnify"
                                   label="Search"
                                   single-line
                                   hide-details
                               ></v-text-field>
                               <v-spacer></v-spacer>
                               <v-btn small color="indigo" dark @click="add_track=true">Add Track By
                               </v-btn>
                           </v-card-title>
                           <v-data-table
                               v-model="selected"
                               :headers="headers"
                               :items="items"
                               :single-select="singleSelect"
                               :sort-by.sync="sortBy"
                               :sort-desc.sync="sortDesc"
                               :search="search"
                               item-key="name"
                               class="elevation-1"
                               :footer-props="{
                              showFirstLastPage: true,
                              firstIcon: 'mdi-arrow-collapse-left',
                              lastIcon: 'mdi-arrow-collapse-right',
                              prevIcon: 'mdi-minus',
                              nextIcon: 'mdi-plus'
                              }"
                           >
                               <template v-slot:item.actions="{ item }">
                                   <v-btn class="mx-1 my-1" fab dark color="indigo" small>
                                       <v-icon dark small @click="editTrack(item)">mdi-pencil</v-icon>
                                   </v-btn>
                                   <span>
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small style="display: none">
                                        <v-icon dark small @click="deleteTrack(item.id)">mdi-delete</v-icon>
                                    </v-btn>
                                    </span>
                               </template>
                           </v-data-table>
                       </v-card>
                   </v-app>
               </div>
               </div>
           </section>
   </div>
</template>
<script>
    import TrackBy from "./TrackBy";
    import datatable from "../../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    import {mapGetters} from "vuex";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_track: false,
                editing: false,
                headers:FieldDefs,
            }
        },
        created(){
            this.listen();
            this.getTracks();
        },
       computed:{
         ...mapGetters({
             tableData:'all_tracks'
         })
       },
        methods:{
            getTracks(){
                this.$store.dispatch('my_tracks').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
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
                    this.getItems();
                    this.add_track =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_track = false;
                    this.editing = false;
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
                });
                }
        },
        components:{
            TrackBy
        }
    }
</script>

<style scoped>

</style>
