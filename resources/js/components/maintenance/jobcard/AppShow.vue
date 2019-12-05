<template>
<div>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">Job Card #101010101</h2>
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
                        <td>Gl9090</td>
                    </tr>
                    <tr>
                        <td>Cost Code</td>
                        <td>{{job.cost_code}}</td>
                    </tr>
                    <tr>
                        <td>Cost Center</td>
                        <td>c232</td>
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
                <table class="customers">
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
                    <tr>
                        <td>234234</td>
                        <td>SDWEWE</td>
                        <td>12</td>
                        <td>VINN</td>
                        <td>4</td>
                        <td>JOHNY</td>
                        <td></td>
                        <td>INV789</td>
                    </tr>

                </table>
                <br>
                <div style="display: flex">
                <table class="customers" style="width: 50%">
                    <tr>
                        <td>STORE ISSUES TOTAL VALUE</td>
                        <td>5000</td>
                    </tr>
                    <tr>
                        <td>LABOUR CHARGES</td>
                        <td>4000</td>
                    </tr>
                    <tr>
                        <td>TRANSPORT CHARGES</td>
                        <td>700</td>
                    </tr>
                    <tr>
                        <td>TOTAL COST OF PROJECT</td>
                        <td>9000</td>
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
                total_cost:0
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
                  console.log(this.job);
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
