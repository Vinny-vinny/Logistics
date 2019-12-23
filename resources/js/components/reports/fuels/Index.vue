<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Reports</h3>
                    <download-excel
                        v-if="fuels.length"
                        class="btn btn-primary pull-right"
                        :data="fuels">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download
                    </download-excel>
                    <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Project</th>
                            <th>Date</th>
                            <th>Litres</th>
                            <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in fuels">
                            <td>{{fuel.Project}}</td>
                            <td>{{fuel.Date}}</td>
                            <td>{{fuel.Quantity}}</td>
                            <td>{{fuel.Reference}}</td>
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
        mounted(){
            this.initDatable();
        },
        computed:{
            fuels(){
                return this.$store.state.fuel_reports;
            }
        },
        methods:{
            back(){
                eventBus.$emit('back');
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
        }
    }
</script>

<style scoped>

</style>
