<template>
<div>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-body" id="printMe">
                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td class="title">
                                            <b>Maintenance Jobcard</b>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="information">
                            <td colspan="4">
                                <table>
                                    <tr>
                                        <td>
                                            Esl, Ltd.<br> 12345 Sunny Road<br> Mombasa, Kenya
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="heading">
                            <td>Maintenance Required</td>
                            <td>Root Cause</td>
                            <td colspan="3">Job Card No. {{job.card_no}}</td>
                        </tr>
                        <tr class="item">
                                <td>
                                    <ul v-for="m in maintenance">
                                    {{m.description}}
                                    </ul>
                                </td>
                            <td>
                                <ul v-for="m in maintenance">
                                    {{m.root_cause}}
                                </ul>
                            </td>
                            <td colspan="3">
                                Asset Reg: {{job.code}} <br>
                                Make / Model: {{job.make}} <br>
                                Km or Svc meter Reading: {{job.current_readings}} {{job.track_name}}<br>
                                Next Service: {{job.next_service_date}}<br>
                                Date Opened: {{job.created}}<br>
                                Time  In: {{job.time_in}}<br>
                                Date Closed: {{job.closed_at}}<br>
                                Time Out: {{job.time_out}}<br>
                            </td>
                        </tr>
                        <tr class="heading">
                            <td>Repair/Service Required</td>
                            <td>Parts</td>
                            <td>Category</td>
                            <td>Qty</td>
                            <td>Cost</td>
                        </tr>
                        <tr class="item">
                            <td>
                                <ul v-for="s_d in service_required">
                                    {{s_d.description}}
                                </ul>
                            </td>
                            <td>
                                <ul v-for="s in services">
                                    {{s.code}} - {{s.description}}
                                </ul>
                            </td>
                            <td>
                                <ul v-for="cat in categories">
                                    {{cat.name}}
                                </ul>
                            </td>
                            <td>
                                <ul v-for="s in service_required">
                                    {{s.qty}}
                                </ul>
                            </td>
                            <td>
                                <ul v-for="s in services">
                                    {{s.cost | number}}
                                </ul>
                            </td>
                        </tr>
                        <tr class="total">
                            <td colspan="3"></td>
                            <td>Total Cost: {{total_cost | number}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="print_item">
                <router-link :to="{path:'/job-card'}" class="btn btn-outline-danger" style="margin-bottom: 10px">Back</router-link>
                <button @click="printDiv('printMe')" class="btn btn-success" style="margin-bottom: 10px"><i class="fa fa-print"></i>Print Job Card</button>
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
        created(){
          this.getJob();
        },

        methods:{
        printDiv(divName){
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;    },
            getJob(){
                axios.get('job-card/'+this.$route.params['id'])
                    .then(job => {
                        this.job = job.data;
                        this.maintenance = JSON.parse(job.data.maintenance);
                        let services = JSON.parse(job.data.service_required);
                        this.service_required = services;
                        let total =0;
                            axios.get('parts')
                                .then(part => {
                                    for (let i=0; i < services.length; i++) {
                                        for(let j=0; j < part.data.length; j++){
                                            if (part.data[j]['id'] === services[i]['part']){
                                                this.total_cost +=parseFloat(part.data[j]['cost'])*parseInt(services[i]['qty']);
                                                this.services.push({id:part.data[j]['id'],code:part.data[j]['code'],description:part.data[j]['description'],cost:parseFloat(part.data[j]['cost'])*parseInt(services[i]['qty'])});
                                            }
                                        }
                                      axios.get('categories')
                                          .then(category => {
                                              for (let c=0; c < category.data.length; c++){
                                                  if (services[i]['category'] === category.data[c]['id']){
                                                     this.categories.push(category.data[c]);
                                                  }
                                              }
                                          })
                                    }

                                })

                    })
            },

        }
    }
</script>

<style>
    .invoice-box {
        max-width: 100%;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(n + 2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading th {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.item input {
        padding-left: 5px;
    }

    .invoice-box table tr.item td:first-child input {
        margin-left: -5px;
        width: 100%;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    .invoice-box input[type="number"] {
        width: 60px;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial,
        sans-serif;
    }

    .rtl table {
        text-align: right;
    }

    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    .print_item{
        margin-left: 20%;
        margin-bottom: 5px;
    }

</style>
