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
                                            <td>
                                                <b>EXPRESS SHIPPING & LOGISTICS</b>
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
                            <tr class="heading" style="width: 100%">
                                <td>Date</td>
                                <td>Invoice No</td>
                                <td>Litres</td>
                                <td>Pump price</td>
                                <td>Cost</td>
                                <td>KM Reading</td>
                                <td>KM/L</td>
                                <td>Others</td>
                                <td>Cost</td>
                            </tr>
                            <tr class="item">
                                <td>{{fuel.fuel_on}}</td>
                                <td>
                                    {{fuel.invoice_no}}
                                </td>
                                <td>
                                    {{fuel.litres}}
                                </td>
                                <td>{{fuel.rate | number}}</td>
                                <td>{{(fuel.rate * fuel.litres) | number}}</td>
                                <td>{{fuel.km_readings | number}}</td>
                                <td>{{fuel.km_readings ? (fuel.km_readings/fuel.odometer_readings): ''}}</td>
                                <td>
                                  <ul v-if="expenses"> <li style="list-style: none">{{expenses.name}}</li></ul>
                                    <ul v-if="other_expenses.length">
                                        <li v-for="o in other_expenses" style="list-style: none">{{o.description}}</li>
                                    </ul>
                                </td>
                                <td>
                                    <ul v-if="expenses"> <li style="list-style: none">{{expenses.cost}}</li></ul>
                                    <ul v-if="other_expenses.length">
                                        <li v-for="o in other_expenses" style="list-style: none">{{o.cost}}</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr class="total">
                                <td colspan="8"></td>
                                <td>Total: {{ (expenses ? (total_cost+expenses.cost) : total_cost) | number}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="print_item">
                    <router-link :to="{path:'/fuel'}" class="btn btn-outline-danger" style="margin-bottom: 10px">Back</router-link>
                    <button @click="printDiv('printMe')" class="btn btn-success" style="margin-bottom: 10px"><i class="fa fa-print"></i> Print Fuel Report</button>
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
            printDiv(divName){
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                },
          getFuel(){
                axios.get(`fuel/${this.$route.params['id']}`)
                    .then(fuel => {
                        this.fuel = fuel.data;
                        let other_expenses = JSON.parse(fuel.data.other_expenses);
                             if (typeof this.fuel.expense_id ==='number'){
                             axios.get('expense')
                                 .then(expense => {
                                     for (let e=0; e < expense.data.length; e++){
                                             if(this.fuel.expense_id === expense.data[e]['id']){
                                               this.expenses = expense.data[e];
                                         }
                                     }
                                 })
                         }
                        if (Object.values(this.fuel.other_expenses[0])[0] !== '' && Object.values(this.fuel.other_expenses[0])[0] !== null ) {
                            axios.get('parts')
                                .then(part =>{
                                    for (let i=0; i< other_expenses.length;i++){
                                        for (let p=0; p < part.data.length; p++){
                                            if (other_expenses[i]['name'] === part.data[p]['id']){
                                                this.total_cost +=part.data[p]['cost']*other_expenses[i]['qty'];
                                                this.other_expenses.push({description:part.data[p]['code'],cost:part.data[p]['cost']*other_expenses[i]['qty']});
                                            }
                                        }
                                    }
                                })
                        }
                    })
          }

        }
    }
</script>

<style scoped>
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

</style>
