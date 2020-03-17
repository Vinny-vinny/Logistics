<template>
<div>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div  style="display:flex">
                <img src="/images/lewa.jpg" alt="Lewa Logo">
                <h2  style="margin-top: 50px;margin-left: 20px;">{{job.category}} Job Card</h2>
               <div style="height: 100%;margin-left: 100px">
              <table v-if="customer">
                <tr><td><b>{{customer.name}}</b></td></tr>
                <tr v-if="customer.address"><td><b>Address</b>: &nbsp;&nbsp;&nbsp;{{customer.address}}</td></tr>
                <tr v-if="customer.telephone"><td><b>Telephone</b>: &nbsp;&nbsp;&nbsp;{{customer.telephone}}</td></tr>
                <tr v-if="customer.box_no"><td><b>Postal Address</b>: &nbsp;&nbsp;&nbsp;{{customer.box_no}}</td></tr>
                <tr v-if="customer.fax"><td><b>Fax</b>: &nbsp;&nbsp;&nbsp;{{customer.fax}}</td></tr>
            </table>
               </div>
            </div>
            <div class="box-body" id="printMe">
                <div style="display: flex">
                <table class="customers">
                    <tr>
                        <td>Job No.</td>
                        <td>{{job.card_no}}</td>
                    </tr>
                    <tr>
                        <td>GL Code</td>
                        <td>{{job.costcode}}</td>
                    </tr>
                    <tr>
                        <td>Cost Code</td>
                        <td>{{job.costcode}}</td>
                    </tr>
                    <tr>
                        <td>Cost Center</td>
                        <td>{{job.cost_center}}</td>
                    </tr>
                    <tr v-if="workshop">
                        <td>Reg or Project</td>
                        <td>{{job.project}}</td>
                    </tr>
                    <tr v-if="workshop">
                        <td>Inspector</td>
                        <td></td>
                    </tr>
                    <tr v-if="workshop">
                        <td>Mechanic</td>
                        <td>{{job.mechanic}}</td>
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
                    <table class="customers" style="margin-left: 40px;margin-top: 120px;" v-if="!workshop">
                        <tr>
                            <td>AUTHORIZED WORKS MANAGER'S SIGNATURE</td>
                            <td style="opacity:0">uuuuuuuuuuuuuuuuuuu</td>
                        </tr>
                    </table>
                    <table class="customers" style="margin-left: 40px;margin-top: 120px;" v-if="workshop">
                        <tr>
                            <td>Make</td>
                            <td>{{job.make}}</td>
                        </tr>
                        <tr>
                            <td>Speed</td>
                            <td style="opacity:0"></td>
                        </tr>
                    </table>
                    </div>
                <hr v-if="workshop">
                <div class="tools" v-if="workshop">
                    <span v-for="tool in tools"><input type="checkbox" class="tool">{{tool}}</span>
                </div>
                <table class="customers" v-if="workshop">
                    <tr>
                        <th>Repair Required</th>
                        <th>Description</th>
                        <th>Mech.Check</th>
                        <th>Super Check</th>
                    </tr>
                    <tr v-for="checklist in checklists">
                        <td>{{checklist.name}}</td>
                        <td>{{checklist.description}}</td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>
                <hr v-if="workshop">
                <table class="customers" v-if="workshop">
                    <tr>
                        <th>Items Required</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                        <th>Received By:</th>
                        <th>Auth By:</th>
                        <th>Remarks</th>
                        <th>Invoice No.</th>
                    </tr>
                    <tr v-for="req in items">
                        <td>{{req.item}}</td>
                        <td>{{req.qty}}</td>
                        <td>{{req.unit_price | number}}</td>
                        <td>{{req.total_value | number}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>
                <table class="customers" v-if="requisition_type =='Internal' && !workshop">
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
                <table class="customers" v-if="requisition_type =='External' && !workshop">
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
                <div style="display: flex" v-if="workshop">
                    <table class="customers" style="width: 50%">
                        <tr>
                            <td>Labour /Con</td>
                            <td>{{job.labour_cost | number}}</td>
                        </tr>
                        <tr>
                            <td>Miscellaneous</td>
                            <td>{{job.standing_fee}}</td>

                        </tr>
                        <tr>
                            <td>Cost</td>
                            <td>{{cost | number}}</td>
                        </tr>
                        <tr>
                            <td>Total Due (Excl)</td>
                            <td>{{(1000 + cost + job.labour_cost) | number}}</td>
                        </tr>
                        <tr>
                            <td>Foreman's sign</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Remarks</td>
                            <td></td>
                        </tr>
                    </table>

                    <div style="margin-left: 40px;">
                        <table class="customers" style="height: 100%;">
                            <tr>
                                <td>Cost at</td>
                                <td>{{cost_at | number}} {{track_by}}</td>
                            </tr>
                            <tr>
                            <td>Time Taken</td>
                            <td>{{hours_spent}} hrs</td>
                            </tr>
                            <tr>
                            <td>Date Completed</td>
                            <td>{{job.complete_date}}</td>
                            </tr>
                            <tr>
                            <td>Out Inspection</td>
                            <td></td>
                            </tr>
                            <tr>
                                <td>Driver's Sign</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div style="display: flex" v-if="!workshop">
                <table class="customers" style="width: 50%">
                    <tr>
                        <td>STORE ISSUES TOTAL VALUE</td>
                        <td>{{cost | number}}</td>
                    </tr>
                    <tr>
                        <td>LABOUR CHARGES</td>
                        <td>{{job.labour_cost | number}}</td>
                    </tr>
                     <tr>
                        <td>STANDING CHARGE FEE</td>
                        <td>{{job.standing_fee | number}}</td>
                    </tr>
                    <tr v-if="has_othercharges">
                        <td>{{other_charges.name}}</td>
                        <td>{{other_charges.cost | number}}</td>
                    </tr>
                    <tr>
                        <td>TOTAL COST OF PROJECT</td>
                        <td>{{(job.labour_cost + cost + job.standing_fee) | number}}</td>
                    </tr>
                </table>
                    <div style="margin-left: 100px; height:100%;margin-top: -20px;">
                        <p style="margin-bottom:12px;margin-top:12px">This information is accurate to my knowledge</p>
                <table class="customers">
                    <tr>
                        <td>CONSTRUCTION MANAGERS SIGN</td>
                        <td style="opacity:0">uuuuuuuuuuuuuuuuuuu</td>
                    </tr>
                </table>
                <p style="margin-bottom:12px;margin-top:12px">I approve the coding allocated to this project</p>
                <table class="customers">
                    <tr>
                        <td>WORKS MANAGERS SIGN</td>
                        <td style="opacity:0">uuuuuuuuuuuuuuuuuuuu</td>
                    </tr>
                </table>
                    </div>
                </div>
                <br>
                <p style="text-align: center;" v-if="!workshop">This JOB CARD is NOT Valid without Manager's Signature</p>

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
                project_cost:0,
                category:'',
                checklists:{},
                tools:[],
                items:[],
                hours_spent:0,
                customer:{},
                requisitions:{},
                cost:0,
                cost_at:0,
                track_by:''
            }
        },
        created() {
          this.getJob();
          this.getRequisitions();
        },
        computed:{
          workshop(){
             return this.category ==='workshop';
          }
        },
        methods:{
            getRequisitions(){
              axios.get('requisitions')
              .then(res => {
                  this.requisitions = res.data;
              })
            },
            getJob(){
              axios.get('job-card')
              .then(res => {
                  this.job = res.data.find(j => j.id == this.$route.params['id']);
                  this.cost_at = this.job.current_readings;
                  this.track_by = this.job.track_name;
                  axios.get('customers')
                  .then(res => {
                     this.customer = res.data.find(c => c.id == this.job.customer_id);
                     console.log(this.customer);
                  });
                  this.category = this.job.category.toLowerCase().trim();
                  //checklist configuration
                  if (this.category == 'workshop'){
                    if (this.job.checklist){
                        axios.get('assign-checklist')
                        .then(res => {
                            let assn_checklist = res.data.find(check => check.id ==this.job.checklist);

                            //tools
                            axios.get('checklists')
                            .then(res => {
                                let checklist = res.data.find(c => c.id == assn_checklist.checklist_id);
                                this.checklists = JSON.parse(checklist.checklists);
                                axios.get('checklist-tool')
                                .then(res => {
                                  this.checklists.forEach(c => {
                                      for(let i=0;i<res.data.length;i++){
                                          if (res.data[i]['id'] == c.tool){
                                              this.tools.push(res.data[i]['name']);
                                          }
                                      }
                                  })
                                })

                            })
                        })
                    }
                  }
                   if (this.job.requisition_id){
                       axios.get('requisitions')
                       .then(res => {
                        let req = res.data.find(r => r.id == this.job.requisition_id);
                        this.requisition = req;
                        if (req.type =='Internal'){
                            this.requisition_type = 'Internal';
                            this.requisitions_internal = req.inventory_items_internal;

                            axios.get('parts')
                            .then(res => {
                                let total =0;
                                this.requisitions_internal.forEach(item => {
                                    for (let i=0;i<res.data.length;i++){
                                        if (res.data[i]['id'] == item.part){
                                            total+=(item.unit_cost*item.quantity);
                                            this.items.push({
                                                'item':res.data[i]['description'],
                                                'qty':item.quantity,
                                                'unit_price':item.unit_cost,
                                                'total_value':item.total_cost,
                                            });
                                        }
                                    }
                                })
                                this.cost = total;
                            })
                        }
                           if (req.type =='External'){
                               this.requisition_type = 'External';
                               this.requisitions_external = req.inventory_items_external;
                               axios.get('parts')
                                   .then(res => {
                                    let total =0;
                                       this.requisitions_external.forEach(item => {
                                           for (let i=0;i<res.data.length;i++){
                                               if (res.data[i]['id'] == item.part){
                                                total+=(item.unit_price*item.quantity);
                                                   this.items.push({
                                                       'item':res.data[i]['description'],
                                                       'qty':item.quantity,
                                                       'unit_price':item.unit_price,
                                                       'total_value':item.total_price,
                                                   });
                                               }
                                           }
                                       })
                                     this.cost = total;
                                   })
                                }
                       })
                   }
                  //Labour calculation = hrs* job type
                  if (this.job.actual_date && this.job.completion_date) {
                      let initialdate = moment(this.job.actual_date).format('YYYY-MM-DD');
                      let enddate = moment(this.job.completion_date).format('YYYY-MM-DD');
                      let start_time = moment(this.job.time_in, "HH:mm:ss").format("hh:mm")
                      let end_time = moment(this.job.time_out, "HH:mm:ss").format("hh:mm");

                      let datetimeA = moment(initialdate + " " + start_time);
                      let datetimeB = moment(enddate + " " + end_time);
                      let time_in_minutes = datetimeB.diff(datetimeA, 'minutes');
                      this.hours_spent = Math.ceil(time_in_minutes/60);
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
        .main-footer{
            display: none;
        }
    }
    ul#menu li {
        display:inline;
    }
    .tool{
        margin-left: 10px;
    }
</style>
