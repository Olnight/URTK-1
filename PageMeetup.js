import { defineComponent } from '../vendor/vue.esm-browser.js';

import UiContainer from './UiContainer.js';
import UiAlert from './UiAlert.js';
import { fetchMeetupById } from '../meetupService.js';
import MeetupView from '../../06-MeetupView/components/MeetupView.js';

export default defineComponent({
  name: 'PageMeetup',

  components: {
    UiAlert,
    UiContainer,
    MeetupView,
  },

  props: {
    meetupId: {
      type: Number,
    },
  },

  data() {
    return {
      meetup: null,
      state: 'loading',
      error: null,
    };
  },

  watch: {
    meetupId() {
      this.state = 'loading';
      this.updateMeetup();
    },
  },

  created() {
    this.updateMeetup();
  },

  methods: {
    async updateMeetup() {
      try{
        let asyMeetup = await fetchMeetupById(this.meetupId); 
        this.meetup = asyMeetup;
        this.state = 'loaded';
      } catch (error) {
        this.state = 'error';
        this.error = error
      }

    },
  },

  template: `
    <div class="page-meetup">
      <!-- meetup view -->
      <MeetupView v-if="state == 'loaded'" :meetup="this.meetup"/>

      <ui-container v-else-if="state == 'loading'">
        <ui-alert>Загрузка...</ui-alert>
      </ui-container>

      <ui-container v-else>
        <ui-alert>{{ error.message }}</ui-alert>
      </ui-container>
    </div>`,
});