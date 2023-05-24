import { defineComponent } from '../vendor/vue.esm-browser.js';
import MeetupAgendaItem from './MeetupAgendaItem.js';

export default defineComponent({
  name: 'MeetupAgenda',
  components: {
    MeetupAgendaItem: MeetupAgendaItem,
  },
  props: {
    agenda: {
      type: Array,
      required: true,
    },
  },
  template: `
    <ul class="agenda">
      <li v-for="agendaItem in agenda" class="agenda__item" >
          <MeetupAgendaItem :agendaItem="agendaItem"/>
      </li>
    </ul>`,
});
