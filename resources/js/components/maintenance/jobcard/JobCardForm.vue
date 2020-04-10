<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div  style="display:flex">
                    <img src="/images/lewa.jpg" alt="Lewa Logo">
                    <h2 style="margin-left:100px">Job Card Form: #{{job.card_no}}</h2>
                </div>
                <div class="box-body">
               <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                       <label>Project</label>
                       <input type="text" class="form-control" :value="job.project">
                   </div>
                   <div class="form-group">
                       <label>Vehicle </label>
                       <input type="text" class="form-control" :value="job.machine">
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
                       <label>Current {{job.track_name}} Readings</label>
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
                       <label>Next {{job.track_name}} Maintenance</label>
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
    import {mapGetters} from "vuex";

    export default {
        props:['job'],
       mounted(){
        this.print();
       },
        methods:{
            print() {
              setTimeout(()=>{
                window.print();
              },500);

                setTimeout(()=>{
                    eventBus.$emit('updatePrint',this.job)
                },1000);
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
