<template>
  <div>
    <div id="nav-lnb">
      <ul>
        <li><router-link :to="{ name: 'userAwardArchiveWinnerList', params: { award: a_id }}">수상작</router-link></li>
        <li><router-link :to="{ name: 'userArchiveAboardList', params: { award: a_id }}">토크샤워</router-link></li>
        <li>
          <v-select
            v-model="a_year"
            :items="archive_year"
            return-object
            append-icon="xi-angle-down-min"
            hide-details
            flat
            solo
            :menu-props="{ contentClass: 'item_select item_archive_year' }"
            class="items_archive_year"
            @input="active_archive_year($event)"
          >
          </v-select>
        </li>
      </ul>
    </div>

    <router-view ref="child_comp"></router-view>
  </div>
</template>

<script>
import { EventBus } from "../../../common/EventBus"

export default {
  data() {
    return {
      a_id: 0,
      a_year: 0,
      archive_year: []
    }
  },
  created() {
    this.fetchItems();
  },
  watch: {
    '$route': 'fetchItems'
  },
  methods: {
    fetchItems() {
      this.$axios.get('/api/get_archive_year_array').then(res => {
        this.archive_year = res.data;

        var urlSplitArr = document.location.href.split("/");
        var slct_type = urlSplitArr[3]
        var a_year = urlSplitArr[4]
        if (5 < urlSplitArr.length) {
          slct_type = urlSplitArr[4]
          a_year = urlSplitArr[5]
        }
        this.a_year = a_year;
        this.a_id = this.$route.params.award ? this.$route.params.award : this.a_year ? this.a_year : 99;

        this.$router.push({ name: this.$route.name, params: { award: this.a_id } });
        this.$refs.child_comp.fetchItems('init');
      });
    },
    active_archive_year(year){
      this.a_id = year;

      this.$router.push({ name: this.$route.name, params: { award: this.a_id } });
      // this.$refs.child_comp.fetchItems('init');

      EventBus.$emit('change-tab', 1);

      if (this.$route.name == 'userAwardArchiveWinnerList') {
        setTimeout(() => {
          this.$vuetify.goTo('#section-winner-list-year');
        }, 0)
      }
    },
  }
}
</script>

<style lang="scss">
@import "@/../../assets/sass/winner.scss";
</style>