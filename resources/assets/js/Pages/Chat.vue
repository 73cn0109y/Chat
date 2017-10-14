<template>
	<div class="chat-container loading" v-if="!chat.info">
		<span class="fa fa-spinner fa-spin"></span>
		Loading...
	</div>

	<div class="chat-container" v-else>
		<div class="chat-header">
			<div class="chat-info">
				<h5>{{ chat.info.title }}</h5>
				<div class="chat-description" v-if="chat.info.description">{{ chat.info.description }}</div>
			</div>
		</div>

		<div class="chat-body" ref="chatBody">
			<div class="chat-message" v-for="message in chatMessages"
			     :class="{ 'is-sender': message.sender.id === myUserID }">
				<div class="user-avatar" v-tooltip="{ content: message.sender.name }">
					{{ message.sender.name.charAt(0) }}
				</div>

				<div class="message-body">
					<div class="message-content">{{ message.message }}</div>
					<div class="message-timestamp">{{ message.createdAt | moment('fromNow', true) }}</div>
				</div>
			</div>
		</div>

		<div class="chat-footer">
			<textarea class="form-control" v-model="message" ref="textarea" placeholder="Send a message..."
			          @keypress="sendMessage" :disabled="states.sendingMessage"></textarea>

			<div class="action-group">
				<button class="btn btn-primary" @click="sendMessage(true)">Send</button>
			</div>
		</div>
	</div>
</template>

<script>
	import Vue from 'vue';
	import axios from 'axios';

	export default {
		data() {
			return {
				chat   : {
					info: {},
				},
				message: '',
				states : {
					sendingMessage: false,
				},
				chatID : NaN,
			}
		},
		created() {
			this.chatID = parseInt(this.$route.params.chat_id);

			if (isNaN(this.chatID))
				return this.$router.push({ name: 'home' });

			this.loadChat();
		},
		methods : {
			loadChat() {
				axios.get(`/api/chat/${this.chatID}`)
				     .then(response => {
					     const data = response.data;

					     if (!data.success)
						     throw response;

					     this.chat.info = data.data;
					     this.autoScroll();
				     })
				     .catch(console.error);
			},
			sendMessage(e) {
				if (e === true || (e.which === 13 && !(e.shiftKey || e.altKey || e.ctrlKey))) {
					if (typeof e !== 'boolean') e.preventDefault();

					this.states.sendingMessage = true;

					axios.post(`/api/chat/${this.chatID}/messages`, {
						message: this.message,
					})
					     .then(response => {
						     const data = response.data;

						     if (!data.success)
							     throw response;

						     this.chatMessages.push(data.data);
						     this.message = '';

						     this.states.sendingMessage = false;
						     this.autoScroll();

						     Vue.nextTick(() => this.$refs.textarea.focus());
					     })
					     .catch(response => {
						     console.error(response);
						     this.states.sendingMessage = false;
					     });
				}
			},
			autoScroll() {
				const $e = this.$refs.chatBody;
				Vue.nextTick(() => $e.scrollTop = $e.scrollHeight);
			},
		},
		computed: {
			chatMessages() {
				return this.chat.info.messages;
			},
			myUserID() {
				return window.userID;
			},
		},
	}
</script>

<style scoped lang="scss">
	@import '../../sass/variables';

	.chat-container {
		display: flex;
		flex-direction: column;
		height: 100%;

		> .chat-header {
			display: flex;
			align-items: center;
			flex: 0 0 50px;
			box-shadow: 0 2px 3px rgba(0, 0, 0, 0.25);
			z-index: 1;
			padding: 0 1em;

			.chat-info {
				> h5 {
					margin: 0;
				}

				.chat-description {
					font-size: 0.8em;
					font-style: italic;
				}
			}
		}

		> .chat-body {
			flex: 1 1 100%;
			background-color: #FFF;
			overflow: hidden;
			overflow-y: auto;

			> .chat-message {
				display: flex;
				margin-bottom: 0.5em;

				&.is-sender {
					> .user-avatar {
						order: 1;
					}

					> .message-body {
						text-align: right;

						> .message-content {
							margin-top: 0.5em;
						}
					}
				}

				> .user-avatar {
					flex: 0 0 40px;
					line-height: 40px;
					text-align: center;
					background-color: $theme-primary-color;
					border-radius: 50%;
					font-weight: bold;
					color: #FFF;
					margin: 0.5em;
				}

				> .message-body {
					flex: 1 1 100%;
					margin: 0.5em;

					> .message-timestamp {
						font-size: 0.8em;
						opacity: 0.75;
					}
				}
			}
		}

		> .chat-footer {
			display: flex;
			align-items: stretch;
			flex: 1 1 100px;
			box-shadow: 0 -2px 3px rgba(0, 0, 0, 0.25);

			.form-control {
				flex: 1 1 100%;
				resize: none;
				border: 0;
				padding: 0.25em 0.5em;

				&::-webkit-input-placeholder {
					color: #333;
				}
			}

			.action-group {
				flex: 0 0 75px;

				> .btn {
					height: 100%;
					margin: 0;
				}
			}
		}
	}
</style>
