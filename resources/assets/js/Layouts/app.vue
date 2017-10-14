<template>
	<div class="app">
		<v-sidebar></v-sidebar>

		<div class="body">
			<router-view></router-view>
		</div>

		<v-tooltip ref="tooltip"></v-tooltip>
	</div>
</template>

<script>
	import VSidebar from '../Components/VSidebar.vue';
	import VTooltip from '../Components/VTooltip.vue';

	export default {
		computed  : {
			tooltip() {
				return this.$refs.tooltip;
			},
		},
		components: {
			VSidebar,
			VTooltip,
		},
	}
</script>

<style scoped lang="scss">
	@import '../../sass/variables';

	.app {
		display: flex;
		height: calc(100% - #{$nav-height});
		overflow: hidden;

		> .v-sidebar {
			flex: 0 0 $sidebar-width;
		}

		> .body {
			flex: 1 1 100%;
		}
	}
</style>
