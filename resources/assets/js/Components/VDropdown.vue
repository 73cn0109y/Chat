<template>
	<div class="v-dropdown" :class="[ { 'open' : isOpen }, getAlignment ]">
		<div class="dropdown-button" @click="toggle">
			<slot name="button" :value="value"></slot>
		</div>

		<div class="dropdown-content" @click="selectOption" ref="dropdownContent">
			<slot name="content"></slot>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				isOpen: false,
				//value : null,
			}
		},
		props   : {
			alignment: {
				type   : String,
				default: 'left',
			},
			value    : null,
		},
		methods : {
			toggle() {
				this.isOpen = !this.isOpen;

				this.$emit('toggle', this.isOpen);

				if (this.isOpen) this.$emit('open');
				else this.$emit('close');
			},
			open() {
				this.isOpen = true;

				this.$emit('open');
			},
			close() {
				this.isOpen = false;

				this.$emit('close');
			},
			selectOption(e) {
				const target = e.target;

				$('> div > .dropdown-item.selected', this.$refs.dropdownContent).each(function () {
					$(this).removeClass('selected');
				});

				target.classList.toggle('selected', true);

				this.$emit('input', target.dataset.item);
				//this.value = target.dataset.item;
				this.close();
			},
		},
		computed: {
			state() {
				return this.isOpen ? 'Open' : 'Closed';
			},
			getAlignment() {
				return 'align-' + this.alignment;
			},
		},
	}
</script>

<style lang="scss">
	@import '../../sass/variables';

	.v-dropdown {
		position: relative;

		&.open {
			> .dropdown-content {
				opacity: 1;
				top: 100%;
				pointer-events: all;
			}
		}

		&.align-right > .dropdown-content {
			left: auto;
			right: 0;
		}

		> .dropdown-button {
			position: relative;
			z-index: 100;
			cursor: pointer;
			padding: 0.75em 0.5em;
			font-size: 0.9em;
			border-bottom: solid 2px rgba(0, 0, 0, 0.1);
		}

		> .dropdown-content {
			position: absolute;
			top: 50%;
			left: 0;
			width: 100%;
			max-height: 200px;
			opacity: 0;
			pointer-events: none;
			z-index: 99;
			line-height: initial;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
			background-color: white;
			transition: top ease-in-out 0.25s, opacity ease-in-out 0.25s;

			.dropdown-item {
				padding: 0.75em 1em;
				cursor: pointer;
				font-size: 0.95em;
				transition: background-color ease-in-out 0.1s;

				&:hover {
					background-color: rgba(0, 0, 0, 0.025);
				}

				&:active {
					background-color: rgba(0, 0, 0, 0.05);
				}

				&.selected {
					color: $theme-primary-color;
					background-color: rgba(0, 0, 0, 0.05);
				}
			}

			.dropdown-seperator {
				border-bottom: solid 1px rgba(0, 0, 0, 0.05);
				margin: 0 1em;
			}
		}
	}
</style>
