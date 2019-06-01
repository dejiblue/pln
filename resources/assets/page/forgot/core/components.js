import Vue from 'vue';

import loader from '../../components/loader';
Vue.component('loader', loader);

import Avatar from '../../components/Avatar';
Vue.component('Avatar', Avatar);

//Bootrap vuejs
import bTable from 'bootstrap-vue/es/components/table/table';
Vue.component('b-table', bTable);

import bNavbar from 'bootstrap-vue/es/components/navbar/navbar';
Vue.component('b-navbar', bNavbar);

import bNavbarBrand from 'bootstrap-vue/es/components/navbar/navbar-brand';
Vue.component('b-navbar-brand', bNavbarBrand);

import bNavbarNav from 'bootstrap-vue/es/components/navbar/navbar-nav';
Vue.component('b-navbar-nav', bNavbarNav);

import bNavItem from 'bootstrap-vue/es/components/nav/nav-item';
Vue.component('b-nav-item', bNavItem);

import { Button } from 'bootstrap-vue/es/components';
Vue.use(Button);

import bForm from 'bootstrap-vue/es/components/form/form';
Vue.component('b-form', bForm);

import bFormGroup from 'bootstrap-vue/es/components/form-group/form-group';
Vue.component('b-form-group', bFormGroup);

import bFormInput from 'bootstrap-vue/es/components/form-input/form-input';
Vue.component('b-form-input', bFormInput);

import bFormInvalidFeedback from 'bootstrap-vue/es/components/form/form-invalid-feedback';
Vue.component('b-form-invalid-feedback', bFormInvalidFeedback);
	
import bContainer from 'bootstrap-vue/es/components/layout/container';
Vue.component('b-container', bContainer);

import bRow from 'bootstrap-vue/es/components/layout/row';
Vue.component('b-row', bRow);

import bCol from 'bootstrap-vue/es/components/layout/col';
Vue.component('b-col', bCol);

import bFormRow from 'bootstrap-vue/es/components/layout/form-row';
Vue.component('b-form-row', bFormRow);

import bPopover from 'bootstrap-vue/es/components/popover/popover';
Vue.component('b-popover', bPopover);

import bModal from 'bootstrap-vue/es/components/modal/modal';
Vue.component('b-modal', bModal);

import bImg from 'bootstrap-vue/es/components/image/img';
Vue.component('b-img', bImg);

import bInputGroup from 'bootstrap-vue/es/components/input-group/input-group';
Vue.component('b-input-group', bInputGroup);

import bProgress from 'bootstrap-vue/es/components/progress/progress';
Vue.component('b-progress', bProgress);

import bAlert from 'bootstrap-vue/es/components/alert/alert';
Vue.component('b-alert', bAlert);

let comp = {

}

export default comp;