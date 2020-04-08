<template>
    <div class="filter-bar ui basic segment grid">
        <div class="ui form">
            <slot name="search">
                <form class="form-inline pull-right" @submit.prevent="doFilter">
                    <div class="form-group">
                        <label>Search for: &nbsp;</label>
                        <input type="text" @keyup="doFilter" v-model="filterText" class="form-control"
                               @keyup.enter="doFilter" placeholder="search name,email,phone">
                    </div>
                </form>
            </slot>

        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                filterText: ''
            }
        },
        methods: {
            doFilter () {
                this.$events.fire('filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = ''
                this.$events.fire('filter-reset')
            }
        }
    }
</script>
