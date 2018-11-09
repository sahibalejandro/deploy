import axios from 'axios';
import sinon from 'sinon';
import moxios from 'moxios';
import Form from 'form-object';
import {mount, createLocalVue} from '@vue/test-utils'
import SitesCreate from '../../resources/js/components/sites.create.vue';

describe('Sites Create', () => {
    let localVue;

    beforeEach(function () {
        // Configure the local vue.
        localVue = createLocalVue();
        localVue.directive('focus', {});

        // Make sure the form object and moxios uses the same axios instance.
        Form.defaults.axios = axios;
        moxios.install(axios);
    });

    afterEach(function () {
        moxios.uninstall(axios);
    });

    it('Fills the form fields', () => {
        let wrapper = mount(SitesCreate, {localVue});

        wrapper.find('#name').setValue('Test Site');
        expect(wrapper.vm.site.name).toBe('Test Site');

        wrapper.find('#git_platform').setValue('bitbucket');
        expect(wrapper.vm.site.git_platform).toBe('bitbucket');

        wrapper.find('#repository').setValue('user/repo');
        expect(wrapper.vm.site.repository).toBe('user/repo');

        wrapper.find('#deployment_script').setValue('npm run production');
        expect(wrapper.vm.site.deployment_script).toBe('npm run production');
    });

    it('Create a new site and redirects to site details', (done) => {
        moxios.stubRequest('sites', {status: 201, response: {id: 123}});

        let wrapper = mount(SitesCreate, {
            localVue,
            mocks: {
                $router: {replace: sinon.spy()}
            }
        });

        wrapper.find('form').trigger('submit');

        moxios.wait(function () {
            let args = {name: 'sites.show', params: {id: 123}};
            expect(wrapper.vm.$router.replace.calledWith(args)).toBe(true);
            done();
        });
    });

});
