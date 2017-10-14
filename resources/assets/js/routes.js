import { Route, Router } from './VueRouteHelper';

// Pages
import Home from './Pages/Home.vue';
import Chat from './Pages/Chat.vue';

export default Router.add([
	Route('', Home)
		.name('home'),
	Route('/:chat_id', Chat)
		.name('chat'),
	Route('*', null)
		.redirect('/'),
], true)
