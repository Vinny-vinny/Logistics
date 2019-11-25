<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_category ? 'Update Category' : 'New Category'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveCategory()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_category ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',
                    id:''
                },
                edit_category: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveCategory(){
                this.edit_category ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('jobcard-category',this.form)
                    .then(res => eventBus.$emit('listJobcardCategories',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`jobcard-category/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_category = false;
                        eventBus.$emit('updateJobcardCategory',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.jobcard_categories
                }
            },
        }
    }
</script>

<style scoped>

</style>
