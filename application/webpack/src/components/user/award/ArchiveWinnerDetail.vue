<template>
	<div>위너</div>
</template>

<script>
var award_global = [];
export default {
	data() {
		console.log("router",this.$route.params.award);
		return {
		}
	},
	methods: {
	},
	mounted() {
		this.$axios.get('/api/service/archive').then(res => {
			award_global = res.data.data;
			console.log(award_global);
		});
	}
}
</script>

<style>

</style>
