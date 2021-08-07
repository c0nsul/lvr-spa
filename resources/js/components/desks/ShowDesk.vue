<template>
    <div class="container">
        <div class="form-group">
            <h3>{{name}}</h3>
            <input @blur="saveName" class="form-control" :class="{ 'is-invalid': $v.name.$error}" type="text" v-model="name">
            <div class="invalid-feedback" v-if="!$v.name.required">
                This field is required!
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength">
                MAX Characters: {{$v.name.$params.maxLength.max}}
            </div>
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
import {required, maxLength} from 'vuelidate/lib/validators';
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
            this.$v.$touch()
            if (this.$v.$anyError){
                return;
            }
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
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255),
        }
    }
}
</script>
