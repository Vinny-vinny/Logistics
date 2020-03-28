<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div  style="display:flex">
                    <img src="/images/lewa.jpg" alt="Lewa Logo">
                    <h2  style="margin-left: 20px;">Fuel Issue Docket</h2>
                    <div style="height: 100%;margin-left: 100px">
                        <table v-if="checkCustomer()">
                            <tr><td><b>&nbsp;&nbsp;&nbsp; {{customer.name}}</b></td></tr>
                            <tr v-if="customer.address"><td><b>Address</b>: &nbsp;&nbsp;&nbsp;{{customer.address}}</td></tr>
                            <tr v-if="customer.telephone"><td><b>Telephone</b>: &nbsp;&nbsp;&nbsp;{{customer.telephone}}</td></tr>
                            <tr v-if="customer.box_no"><td><b>Postal Address</b>: &nbsp;&nbsp;&nbsp;{{customer.box_no}}</td></tr>
                            <tr v-if="customer.fax"><td><b>Fax</b>: &nbsp;&nbsp;&nbsp;{{customer.fax}}</td></tr>
                        </table>
                    </div>
                </div>
                <p style="text-align: center;margin-right: 300px;font-size: 16px">Number: {{fuel.fuel_no}}</p>
                <div class="box-body" id="printMe">

                    <div>
                        <table class="customers" style="width: 50%">
                            <tr>
                                <td>Date</td>
                                <td>{{fuel.date_fueled}}</td>
                            </tr>
                            <br>
                            <tr>
                                <td>Dept/Project/Sale</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Registration</td>
                                <td>{{fuel.vehicle}}</td>
                            </tr>
                            <tr>
                                <td>{{fuel.track_by}} Readings</td>
                                <td>{{fuel.current_readings}}</td>
                            </tr>
                            <br>
                            <tr>
                                <td>Person Requesting</td>
                                <td>{{fuel.requested_by}}</td>
                            </tr>
                            <tr>
                                <td>Sign</td>
                                <td></td>
                            </tr>
                            <br>
                            <tr>
                                <td>Person Authorizing</td>
                                <td>{{fuel.person_authorizing}}</td>
                            </tr>
                            <tr>
                                <td>Sign</td>
                                <td></td>
                            </tr>
                            <br>
                            <tr>
                                <td>Litres Issued</td>
                                <td>{{fuel.litres}}</td>
                            </tr>
                            <tr>
                                <td>End Meter Reading</td>
                                <td>{{fuel.odometer_readings}}</td>
                            </tr>
                            <br>
                            <tr>
                                <td>Storeman</td>
                                <td>{{fuel.store_man}}</td>
                            </tr>
                            <tr>
                                <td>Sign</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>

                </div>
               <div class="row print">
                   <div class="form-group">
                       <button class="btn btn-primary" @click="print()" style="margin-left: 50px;"> <i class="fa fa-print"></i> Print</button>
                       <button @click="back()" class="btn btn-outline-warning"> Back</button>
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
              fuel:{},
              expenses:{},
                other_expenses:[],
                total_cost:0,
                customer:{}
            }
        },
        created(){
         this.getAllDeatils();
        },
        methods:{
            back(){
              eventBus.$emit('cancel');
            },
            getAllDeatils(){
              this.fuel = this.$store.state.fuels;
              this.customer = this.$store.state.customers;
            },
            checkCustomer(){
              return  this.customer !==undefined;
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
