<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Price Lists</h3>
                    <button class="btn btn-success pull-right" @click="importPrices()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Pricelist Name ID</th>                      
                            <th>Exclusive Price</th>
                            <th>Inclusive Price</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="price in tableData">
                            <td>{{price.id}}</td>                         
                            <td>{{price.price_list_name_id}}</td>
                             <td>{{price.exclusive_price}}</td>
                             <td>{{price.inclusive_price}}</td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>

    export default {
        data(){
            return {
               tableData: [],
               importing:false,


            }
        },
        created(){
            this.getPriceLists();
        },
       
        methods:{
            getPriceLists(){
                axios.get('price-list')
                    .then(res => {
                      this.tableData = res.data
                      this.initDatable();
                    })
            },
            importPrices(){
                this.importing = true;
                    axios.get(`import-price-list`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Price Lists successfully imported.');
                        this.$router.go();
                    })
            },

            initDatable(){
                setTimeout(()=>{
                    $('.dt').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        order: [[ 0, 'asc' ], [ 3, 'desc' ]],
                        responsive: true,
                        destroy: true,
                        retrieve:true,
                        autoFill: true,
                        colReorder: true,

                    });
                },1000)
            },
        },

    }
</script>

<style scoped>

</style>
