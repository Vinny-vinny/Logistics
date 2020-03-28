<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div  style="display:flex">
                    <img src="/images/lewa.jpg" alt="Lewa Logo">
                    <h2 style="margin-left:100px">Job Card Form: #{{job_card}}</h2>
                </div>
                <div class="box-body">
               <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                       <label>Project</label>
                       <input type="text" class="form-control" :value="project">
                   </div>
                   <div class="form-group">
                       <label>Vehicle </label>
                       <input type="text" class="form-control" :value="machine">
                   </div>
                   <div class="form-group">
                       <label>Category</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Job Type</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Customer Type</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Customer Name</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Cost Code</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Fuel Balance(L)</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Current {{track_by}} Readings</label>
                       <input type="text" class="form-control">
                   </div>
               </div>
               <div class="col-md-6">
                   <div class="form-group">
                       <label>Service Type</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Servicing Date</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Servicing Time In</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Servicing Completion Date</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Servicing Time Out</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Next Service Date</label>
                       <input type="text" class="form-control">
                   </div>
                   <div class="form-group">
                       <label>Next {{track_by}} Maintenance</label>
                       <input type="text" class="form-control">
                   </div>
               </div>
               </div>
                    <!-- OUTPUT -->
                    <button @click="print" class="btn btn-primary" id="button-container">Print Form</button>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        props:['printJobForm'],
        data(){
           return {
               job_card:'',
               machine:'',
               track_by:'',
               jobcard_type:'',
               project:''
           }
        },
       mounted(){
         this.jobInfo();
       },
        computed:{
            service_type(){
                return this.jobcard_type === 'Internal';
            },
        },
        methods:{
            jobInfo(){
                axios.get('job-card')
                    .then(res =>{
                        let job_details = res.data.jobcards.find(job => job.id == this.$route.params['id']);
                        this.job_card = job_details.card_no;
                        this.project = job_details.project;
                        this.jobcard_type = job_details.service_type;
                        axios.get('machines')
                            .then(res => {
                                this.machine = res.data.find(vehicle => vehicle.id = job_details.machine_id).code;
                            });
                        axios.get('track-by')
                            .then(res => {
                                this.track_by = res.data.find(track => track.id == job_details.track_by_id).name;
                            });
                       this.print();
                    });

            },
            print() {
              setTimeout(()=>{
                  window.print();
              },500);

              setTimeout(()=>{
              this.$router.push('/job-card');
            },1000)
            }

        }
    }
</script>

<style scoped>
    @media print {
        #button-container{
            display: none;
        }
    }
</style>
