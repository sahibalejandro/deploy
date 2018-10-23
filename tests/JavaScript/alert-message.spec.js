import { mount } from '@vue/test-utils';
import AlertMessage from '../../resources/js/components/alert-message.vue';

describe('Alert Message', () => {

    it('Displays an alert message', () => {
        let wrapper = mount(AlertMessage, {
            propsData: {
                messages: [],
            },
        });

        expect(wrapper.isEmpty()).toBe(true);

        wrapper.setProps({
            messages: [{type: 'success', text: 'Success message!'}]
        });

        expect(wrapper.isEmpty()).toBe(false);

        let alert = wrapper.find('.alert');
        expect(alert.classes()).toContain('alert-success');
        expect(alert.text()).toBe('Success message!');
    });

});
