<template>
<div>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <h2 style="text-align: center;margin-right: 300px;">{{job.category}} Job Card</h2>
            <div class="box-body" id="printMe">
                <div style="display: flex">
                <table class="customers">
                    <tr>
                        <td>Job No.</td>
                        <td>{{job.card_no}}</td>
                    </tr>
                    <tr>
                        <td>GL Code</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Cost Code</td>
                        <td>{{job.cost_code}}</td>
                    </tr>
                    <tr>
                        <td>Cost Center</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Date Start</td>
                        <td>{{job.start_date}}</td>
                    </tr>
                    <tr>
                        <td>Date Complete</td>
                        <td>{{job.start_date}}</td>
                    </tr>

                </table>
                    <br>
                    <table class="customers" style="margin-left: 40px;margin-top: 120px;">
                        <tr>
                            <td>AUTHORIZED WORKS MANAGER'S SIGNATURE</td>
                            <td style="opacity:0">uuuuuuuuuuuuuuuuuuu</td>
                        </tr>
                    </table>
                    </div>
                <hr>
                <table class="customers" v-if="requisition_type =='Internal'">
                    <tr>
                        <th>DATE</th>
                        <th>DESCRIPTION</th>
                        <th>QTY</th>
                        <th>AUTHORIZED BY</th>
                        <th>QTY ISSUED</th>
                        <th>RECEIVED BY</th>
                        <th>SIGN</th>
                        <th>INVOICE NO</th>
                    </tr>
                    <tr v-for="req in requisitions_internal">
                        <td>{{requisition.date_requested}}</td>
                        <td>{{requisition.description}}</td>
                        <td>{{req.quantity}}</td>
                        <td>{{requisition.person_requested}}</td>
                        <td>{{req.quantity}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>
                <table class="customers" v-if="requisition_type =='External'">
                    <tr>
                        <th>DATE</th>
                        <th>DESCRIPTION</th>
                        <th>QTY</th>
                        <th>AUTHORIZED BY</th>
                        <th>QTY ISSUED</th>
                        <th>RECEIVED BY</th>
                        <th>SIGN</th>
                        <th>INVOICE NO</th>
                    </tr>
                    <tr v-for="req in requisitions_external">
                        <td>{{requisition.date_requested}}</td>
                        <td>{{requisition.description}}</td>
                        <td>{{req.quantity}}</td>
                        <td>{{requisition.person_requested}}</td>
                        <td>{{req.quantity}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>
                <hr v-if="has_fuel">
                <h3 style="text-align: center;margin-right: 400px;font-size: 16px;font-weight: 800" v-if="has_fuel">Fuel Docket</h3>
                <table class="customers" style="width: 50%" v-if="has_fuel">
                    <tr>
                        <td>Date</td>
                        <td>{{fuel_docket.date_fueled}}</td>
                    </tr>
                    <br>
                    <tr>
                        <td>Dept/Project/Sale</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Registration</td>
                        <td>{{fuel_docket.vehicle}}</td>
                    </tr>
                    <tr>
                        <td>{{fuel_docket.track_by}} Readings</td>
                        <td>{{fuel_docket.current_readings}}</td>
                    </tr>
                    <br>
                    <tr>
                        <td>Person Requesting</td>
                        <td>{{fuel_docket.requested_by}}</td>
                    </tr>
                    <tr>
                        <td>Sign</td>
                        <td></td>
                    </tr>
                    <br>
                    <tr>
                        <td>Person Authorizing</td>
                        <td>{{fuel_docket.person_authorizing}}</td>
                    </tr>
                    <tr>
                        <td>Sign</td>
                        <td></td>
                    </tr>
                    <br>
                    <tr>
                        <td>Litres Issued</td>
                        <td>{{fuel_docket.litres}}</td>
                    </tr>
                    <tr>
                        <td>End Meter Reading</td>
                        <td>{{fuel_docket.odometer_readings}}</td>
                    </tr>
                    <br>
                    <tr>
                        <td>Storeman</td>
                        <td>{{fuel_docket.store_man}}</td>
                    </tr>
                    <tr>
                        <td>Sign</td>
                        <td></td>
                    </tr>
                </table>

                <br>
                <div style="display: flex">
                <table class="customers" style="width: 50%">
                    <tr>
                        <td>STORE ISSUES TOTAL VALUE</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>LABOUR CHARGES</td>
                        <td>{{job.labour_cost | number}}</td>
                    </tr>
                    <tr v-if="has_othercharges">
                        <td>{{other_charges.name}}</td>
                        <td>{{other_charges.cost | number}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL COST OF PROJECT</td>
                        <td>{{project_cost | number}}</td>
                    </tr>
                </table>

                    <div style="margin-left: 40px">
                        <p style="margin-bottom:0">This information is accurate to my knowledge</p>
                <table class="customers">
                    <tr>
                        <td>CONSTRUCTION MANAGERS SIGN</td>
                        <td style="opacity:0">uuuuuuuuuuuuuuuuuuu</td>
                    </tr>
                </table>
                <p style="margin-bottom:0">I approve the coding allocated to this project</p>
                <table class="customers">
                    <tr>
                        <td>WORKS MANAGERS SIGN</td>
                        <td style="opacity:0">uuuuuuuuuuuuuuuuuuuu</td>
                    </tr>
                </table>
                    </div>

                </div>

                <br>
                <p style="text-align: center;">This JOB CARD is NOT Valid without Manager's Signature</p>

            </div>
            <div class="row print">
                <div class="form-group">
                    <button class="btn btn-primary" @click="print()" style="margin-left: 50px;"> <i class="fa fa-print"></i> Print</button>
                    <router-link to="/job-card" class="btn btn-outline-warning"> Back</router-link>
                </div>
            </div>
        </div>
    </section>
</div>
</template>

<script>
    export default {
        data(){
            return {
                job:{},
                maintenance:{},
                services:[],
                categories:[],
                service_required:{},
                total_cost:0,
                requisitions_internal:{},
                requisitions_external:{},
                fuel_docket:{},
                requisition_type:'',
                requisition:{},
                has_fuel:false,
                other_charges:{},
                has_othercharges:false,
                project_cost:0
            }
        },
        created() {
          this.getJob();
        },
        methods:{
            getJob(){
              axios.get('job-card')
              .then(res => {
                  this.job = res.data.find(j => j.id == this.$route.params['id']);
                   if (this.job.requisition_id){
                       axios.get('requisitions')
                       .then(res => {
                        let req = res.data.find(r => r.id == this.job.requisition_id);
                        this.requisition = req;
                        if (req.type =='Internal'){
                            this.requisition_type = 'Internal';
                            this.requisitions_internal = JSON.parse(req.inventory_items_internal);
                        }
                           if (req.type =='External'){
                               this.requisition_type = 'External';
                               this.requisitions_external = JSON.parse(req.inventory_items_external);
                                }
                       })
                   }
                  //Labour calculation = hrs* job type
                  if (this.job.closed_at) {
                      let initialdate = moment(this.job.actual_date).format('YYYY-MM-DD');
                      let enddate = moment(this.job.closed_at).format('YYYY-MM-DD');
                      let start_time = moment(this.job.time_in, "HH:mm:ss").format("hh:mm")
                      let end_time = moment(this.job.time_out, "HH:mm:ss").format("hh:mm");

                      let datetimeA = moment(initialdate + " " + start_time);
                      let datetimeB = moment(enddate + " " + end_time);
                      let time_in_minutes = datetimeB.diff(datetimeA, 'minutes');

                      if (this.job.job_type_id !== '') {
                          axios.get('job-types')
                              .then(res => {
                                  this.job_types = res.data;
                                  let cost = this.job_types.find(type => type.id == this.job.job_type_id).hourly_rate;
                                  this.project_cost = Math.abs(time_in_minutes / 60 * cost);
                              })

                      }
                  }
                 if (this.job.fuel){
                   this.has_fuel = true;
                    axios.get('fuel')
                    .then(res => {
                        this.fuel_docket = res.data.find(f => f.id == this.job.fuel);
                        if (this.fuel_docket.expense_id){
                            this.has_othercharges = true;
                            axios.get('expense')
                                .then(res => {
                                    this.other_charges = res.data.find(charge => charge.id ==this.fuel_docket.expense_id);
                                })
                        }
                    })
                 }
              })
            },
            print(){
                window.print();
            }

        }
    }
</script>

<style scoped>
    .customers {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    .customers td, .customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    .customers tr:nth-child(even){background-color: #f2f2f2;}

    .customers tr:hover {background-color: #ddd;}

    .customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: gray;
        color: white;
    }
    @media print {
        .print {
            display: none;
        }
    }
</style>
