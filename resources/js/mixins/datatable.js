export default {
    data(){
        return {
            totalItems: 0,
            search: '',
            sortBy: 'id',
            items: [],
            sortDesc: false,
            loading: true,
            singleSelect: false,
            selected: [],
            pagination: {
                descending: true,
                page: 1,
                rowsPerPage: '',
                sortBy: '',
                totalItems: 0,
                rowsPerPageItems: [1, 2, 4, 8, 16]
            },
        }
    },
    methods: {
        initDate() {
            var vm = this;
            $('.time_in').timepicker({'scrollDefault': 'now'}).on('changeTime', function () {
                vm.form.time_in = $('.time_in').val();
            })
            $('.time_out').timepicker({'scrollDefault': 'now'}).on('changeTime', function () {
                vm.form.time_out = $('.time_out').val();
            })
        },
        getItems(){
            if (this.tableData.length > 0 ) {
                this.getDataFromApi()
                    .then(data => {
                        this.items = data.items
                        this.totalItems = data.total
                    })
            }
        },
        getDataFromApi() {
            this.loading = true
            return new Promise((resolve, reject) => {
                const {sortBy, descending, page, rowsPerPage} = this.pagination

                let items = this.tableData;
                const total = items.length

                if (this.pagination.sortBy) {
                    items = items.sort((a, b) => {
                        const sortA = a[sortBy]
                        const sortB = b[sortBy]

                        if (descending) {
                            if (sortA < sortB) return 1
                            if (sortA > sortB) return -1
                            return 0
                        } else {
                            if (sortA < sortB) return -1
                            if (sortA > sortB) return 1
                            return 0
                        }
                    })
                }

                if (rowsPerPage > 0) {
                    items = items.slice((page - 1) * rowsPerPage, page * rowsPerPage)
                }
                    this.loading = false
                    resolve({
                        items,
                        total
                    })

            })
        },

    }
}
