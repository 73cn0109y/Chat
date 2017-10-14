import Vue from 'vue';
import moment from 'moment';

Vue.filter('moment', (value, method, utc = false, ...args) => {
	if (!value) return value;
	const m = moment(utc ? moment.utc(value) : value).local();
	return m[ method ].apply(m, args);
});

Vue.filter('commaNumber', (v) => {
	v = v.toString();
	for (let i = v.length - 3; i > 0; i -= 3)
		v = v.slice(0, i) + ',' + v.slice(i, v.length);
	return v;
});
