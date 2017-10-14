<template>
	<div class="v-sidebar">
		<div class="search-bar">
			<input class="form-control inverse" placeholder="Search for Users" v-model="search.query"
			       @input="debounceSearch">
		</div>

		<div class="search-results-container" v-if="search.results || search.searching">
			<div class="no-results" v-if="search.results.length <= 0">
				No Users Found!
			</div>

			<div class="search-item" v-for="user in search.results" @click="createConversation(user.id)" v-else>
				<h5>{{ user.name }}</h5>
				<div class="user-email">{{ user.email }}</div>
			</div>
		</div>

		<div class="conversations-container" v-else>
			<div class="conversation-item" v-for="conversation in conversations"
			     @click="openConversation(conversation.id)">
				<div class="conversation-title">
					<h5>{{ conversation.title }}</h5>

					<div class="conversation-description" v-if="conversation.description">
						{{ conversation.description }}
					</div>
				</div>

				<div class="conversation-members-count">
					{{ conversation.membersCount | commaNumber }} member{{ conversation.membersCount === 1 ? '' : 's' }}
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import axios from 'axios';

	export default {
		data() {
			return {
				conversations: [],
				search       : {
					query    : null,
					debouncer: null,
					searching: false,
					results  : null,
				},
			}
		},
		created() {
			this.loadConversations();
		},
		methods: {
			loadConversations() {
				axios.get(`/api/chat`)
				     .then(response => {
					     const data = response.data;

					     if (!data.success)
						     throw response;

					     this.conversations = data.data;
				     })
				     .catch(console.error);
			},
			openConversation(id) {
				this.$router.push({
					name  : 'chat',
					params: {
						chat_id: id,
					},
				});
			},
			debounceSearch() {
				if (this.search.searching) return;

				if (this.search.debouncer)
					clearTimeout(this.search.debouncer);

				this.search.debouncer = setTimeout(this.performSearch.bind(this), 500);
			},
			performSearch() {
				this.search.searching = true;

				axios.get(`/api/search/users?q=${this.search.query}`)
				     .then(response => {
					     const data = response.data;

					     if (!data.success)
						     throw response;

					     this.search.results   = data.data.data;
					     this.search.searching = false;
				     })
				     .catch(response => {
					     console.error(response);
					     this.search.searcing = false;
				     });
			},
			createConversation(userID) {
				axios.post(`/api/chat`, {
					userID,
				})
				     .then(response => {
					     const data = response.data;

					     this.$router.push({
						     name  : 'chat',
						     params: {
							     chat_id: data.data.id,
						     },
					     });
				     })
				     .catch(console.error);
			},
		},
	}
</script>

<style scoped lang="scss">
	@import '../../sass/variables';

	.v-sidebar {
		background-color: darken($theme-primary-color, 5%);
		box-shadow: 3px 0 3px rgba(0, 0, 0, 0.5);
		color: #EEE;
		z-index: 1;

		> .search-bar > .form-control {
			color: inherit;
		}

		> .search-results-container {
			display: flex;
			flex-direction: column;

			> .no-results {
				flex: 0 0 auto;
				text-align: center;
			}

			> .search-item {
				padding: 0.25em 0.75em;
				margin: 0.5em 0;
				cursor: pointer;
				transition: background-color ease-in-out 0.25s;

				&:first-child {
					margin-top: -0.25em;
				}

				&:last-child {
					margin-bottom: 0;
				}

				&:nth-child(odd) {
					background-color: rgba(0, 0, 0, 0.1);
				}

				&:hover {
					background-color: rgba(0, 0, 0, 0.25);
				}

				> h5 {
					margin: 0;
				}

				.user-email {
					font-size: 0.8em;
					font-style: italic;
				}
			}
		}

		> .conversations-container {
			display: flex;
			flex-direction: column;

			> .conversation-item {
				display: flex;
				padding: 0.25em 0.75em;
				margin: 0.5em 0;
				cursor: pointer;
				transition: background-color ease-in-out 0.25s;

				&:first-child {
					margin-top: -0.25em;
				}

				&:last-child {
					margin-bottom: 0;
				}

				&:nth-child(odd) {
					background-color: rgba(0, 0, 0, 0.1);
				}

				&:hover {
					background-color: rgba(0, 0, 0, 0.25);
				}

				> .conversation-title {
					flex: 1 1 100%;

					> h5 {
						margin: 0;
					}

					.conversation-description {
						font-size: 0.8em;
						font-style: italic;
					}
				}

				> .conversation-members-count {
					flex: 0 0 auto;
					margin-left: 1em;
					font-size: 0.75em;
				}
			}
		}
	}
</style>
