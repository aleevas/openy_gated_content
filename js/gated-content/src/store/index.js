import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist';
import auth from './modules/auth';
import authDummy from './modules/auth/dummy';
import authCustom from './modules/auth/custom';
import authPersonify from './modules/auth/personify';

Vue.use(Vuex);

const vuexLocalStorage = new VuexPersistence({
  key: 'vuex',
  storage: window.localStorage,
  reducer: (state) => ({
    auth: {
      user: state.auth.user,
      loggedIn: state.auth.loggedIn,
    },
  }),
});

export default new Vuex.Store({
  state: { },
  mutations: { },
  actions: { },
  modules: {
    auth,
    authDummy,
    authCustom,
    authPersonify,
  },
  plugins: [vuexLocalStorage.plugin],
});
