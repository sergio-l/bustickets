Vue.component('checkbox-return', Vue.extend({
    props: {
        id: Number,
        suma:Number,
        percent:Number,
        it:Number
    },
    data () {
        return {
            visible: false
        }
    },
    mounted () {
        //....
    },
    methods: {
        total:function(){
            return this.suma - (this.suma / 100 * this.percent);
        },

    },
    computed: {
        percentSuma:function () {
            return this.total();
        }
    },

    template: '<div class="checkbox"><label><input type="checkbox" v-model="visible" v-bind:name="`ticket[${it}][id]`"  ' +
        ':value="id"><strong style="color:red;">Сделать возврат (%{{percent}})</strong></label>' +
        '<p v-if="visible"" style="color:blue;"><strong>Сумма возврата: {{percentSuma}} руб.</strong></p>' +
        '<input type="hidden" v-bind:name="`ticket[${it}][sum]`" :value="percentSuma"> </div>'
}));

Vue.component('total', Vue.extend({
    data(){
        return {
            suma: 0
        }
    },

    methods:{
        getTotal:function(event){
            this.$emit('sumaUpdated', this.suma);
            console.log( this.$emit('sumaUpdated', this.suma+=5));
        }

    },

    computed:{
        test:function () {
            console.log(this.$root);
            return this.$root.suma;
        }
    },


    template:"<h3>Suma: {{test}}</h3>"
}));


Vue.component('addDriver', Vue.extend({
    props: {
        id: Number,

    },
    data () {
        return {
            visible: false
        }
    },
    mounted () {
        //....
    },
    methods: {

    },
    computed: {
        percentSuma:function () {
            
        }
    },

    template: '<button id="add_st" class="btn btn-primary" type="button">Добавить водителя</button>'
}));

