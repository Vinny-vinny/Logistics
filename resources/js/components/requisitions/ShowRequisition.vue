<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div  style="display:flex">
                    <img src="/images/lewa.jpg" alt="Lewa Logo">
                    <h2  style="margin-left: 20px;">STORES REQUISITION NOTE</h2>
                    <p style="margin-left:100px">SR: {{requisition.req_no}}</p>
                </div>

                <div class="box-body" id="printMe">
                    <div style="display: flex">
                        <table class="customers">
                            <tr>
                                <td>Cost Dept.</td>
                                <td>{{requisition.project}}</td>
                            </tr>
                            <tr>
                                <td>Charge To:</td>
                                <td>{{requisition.where_to_charge}}</td>
                            </tr>
                            <tr>
                                <td>Person Requesting & Signature</td>
                                <td>{{requisition.person_requested}}</td>
                            </tr>
                            <tr>
                               <td>Narration</td>
                                <td>{{requisition.description}}</td>
                            </tr>

                        </table>
                        <br>
                        <table class="customers" style="margin-left: 40px;">
                            <tr>
                                <td>Date</td>
                                <td>{{requisition.date_requested}}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <table class="customers">
                        <tr>
                            <th>Item Description</th>
                            <th>Qty</th>
                            <th>Cost Code</th>
                        </tr>
                        <tr v-for="item in items">
                            <td>{{item.description}}</td>
                            <td>{{item.qty}}</td>
                            <td></td>
                        </tr>
                    </table>
                    <br>
                    <p>Section Head - Requsting Dept: &nbsp;&nbsp;&nbsp; .............................................................</p>
                    <p>HOD - Administration / Stores: &nbsp;&nbsp;&nbsp; .............................................................</p>

                </div>
                <div class="row print">
                    <div class="form-group">
                        <button class="btn btn-primary" @click="print()" style="margin-left: 50px;"> <i class="fa fa-print"></i> Print</button>
                        <router-link to="/requisitions" class="btn btn-outline-warning"> Back</router-link>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        data(){
            return {
            items:[],
            requisition:{}
            }
        },
        created() {
        this.getRequisitions();
            },
        computed:{
          ...mapGetters({
              requisitions:'all_reqs',
              parts:'all_parts'
          })
        },
        methods:{
            getRequisitions(){
                    let items;
                    this.requisition = this.requisitions.find(req => req.id == this.$route.params['id']);
                   if (this.requisition.type ==='Internal'){
                       items = this.requisition.inventory_items_internal;
                   }
                   else{
                       items = this.requisition.inventory_items_external;
                   }

                        for (let j=0;j<items.length;j++){
                            for (let i =0; i<this.parts.length;i++){
                                if (this.parts[i]['id'] ==items[j]['part']){
                                    this.items.push({
                                        description:this.parts[i]['description'],
                                        qty:items[j]['quantity']
                                })
                                }
                            }
                        }
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
