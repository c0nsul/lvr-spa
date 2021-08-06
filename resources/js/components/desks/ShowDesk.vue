<template>
    <div class="conteiner">
        <div class="form-group">
            <input @blur="saveName" class="form-control" type="text" v-model="name">
        </div>


        <div class="alert alert-danger" role="alert" v-if="error">
            Erorr data loading!
        </div>

        <button class="btn btn-primary" type="button" disabled v-if="loading">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>

    </div>
</template>

<script>
export default {
    props: [
        'deskId',
    ],
    data(){
        return {
            name: null,
            error: false,
            loading: true
        }
    },
    methods:{
        saveName(){
            axios.post('/api/V1/desks/'+this.deskId, {
                _method: 'PUT',
                name: this.name,
            })
            .then(response => {
            })
            .finally(()=>{
                this.loading = false;
            })
            .catch(error =>{
                console.log(error);
                this.error = true;
            })
        }
    },
    mounted() {
        axios.get('/api/V1/desks/'+this.deskId)
            .then(response => {
                this.name = response.data.data.name;
            })
            .finally(()=>{
                this.loading = false;
            })
            .catch(error =>{
                console.log(error);
                this.error = true;
            })
    }
}
</script>
