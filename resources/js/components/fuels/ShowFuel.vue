<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title">Fuel Issue Docket</h2>
                </div>
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
                                <td></td>
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
                       <router-link to="/fuel" class="btn btn-outline-warning"> Back</router-link>
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
                total_cost:0
            }
        },
        created(){
            this.getFuel();
        },
        methods:{
            getFuel(){
              axios.get('fuel')
                .then(res => {
                    this.fuel = res.data.find(f => f.id == this.$route.params['id']);

                    console.log(this.fuel);
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
