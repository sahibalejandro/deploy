import { mount } from '@vue/test-utils';
import EnvFile from '../../resources/js/components/env-file.vue';

describe('Edit .env file', () => {
    let wrapper;

    beforeEach(function () {
        wrapper = mount(EnvFile, {
            propsData: {
                siteId: 1,
                initialContent: 'APP_NAME=Test',
            },
        });
    });

    it('Display a text area to edit a .env file', () => {
        let content = wrapper.find('#content');
        expect(content.exists()).toBe(true);
        expect(content.element.value).toBe('APP_NAME=Test');
    });

});
