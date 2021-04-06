<template>
  <div class="container container-full-width" :style="styledText" v-scroll="onScroll">
    <div class="container container-basic container-first">
      <div class="title_area">
        <h1 class="title-1 title-winner title-archive"><span :style="styledStroke">아카이브</span><br>앤어워드 수상작</h1>
        <div class="title-sub-1 title-winner" :style="styledText">공정한 심사를 거쳐 선정된 앤어워드의 수상작들을 확인해보세요</div>
      </div>
    </div>

    <section id="section-winner-list-cover" class="section section-winner-list section-winner-list-cover">
      <div class="txt_wrap" :style="styledTextbyBg">A.N.D. AWARD</div>
      <div class="container container-inner container-basic">
        <v-icon>xi-arrow-down</v-icon>
      </div>
    </section>


    <section id="section-winner-list-year" class="section section-winner-list">
      <div class="container container-inner container-basic">
        <div class="year">{{a_id}}</div>
        <ul class="tab_category">
          <li v-for="(category, index) in categories" :key="category.c_id" @click="get_winners(index+1)" :class="{ active: category_type == index+1}" :style="category_type == index+1 ? styledBgwidthBoder : ''">{{category.c_title}}</li>
        </ul>
      </div>
    </section>

    <section class="section section-winner-main section-winner-category">
      <div class="container container-inner container-basic">
        <div v-for="(winners, index) in winner_all" :key="winners.c_id" class="subcategory_wrap">
          <div v-if="winners.child">
            <div class="subcategory">{{winners.c_title}}</div>
            <!-- <div class="subdsc">{{winners.c_describe}}</div> -->
            <div class="winner_wrap">
              <div>
                <div v-if="has_winner('K', winners.child)" class="stit">{{ prizeSpecial('K') }}</div>
                <ul v-if="has_winner('K', winners.child)" class="list-winner list-winner2">
                  <li v-for="(winner, index) in winners.child" :key="winner.w_id" v-if="is_winner('K', winner.z_title)" @click="openWinnerDetail(winner.w_id)">
                    <div class="thumb">
                      <v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
                      <v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
                    </div>
                    <div class="tit">{{winner.w_title}}</div>
                    <div class="company">{{winner.e_company}}</div>
                  </li>
                </ul>
              </div>
              <div>
                <div v-if="has_winner('S', winners.child)" class="stit">{{ prizeSpecial('S') }}</div>
                <ul v-if="has_winner('S', winners.child)" class="list-winner list-winner2">
                  <li v-for="(winner, index) in winners.child" :key="winner.w_id" v-if="is_winner('S', winner.z_title)" @click="openWinnerDetail(winner.w_id)">
                    <div class="thumb">
                      <v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
                      <v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
                    </div>
                    <div class="tit">{{winner.w_title}}</div>
                    <div class="company">{{winner.e_company}}</div>
                  </li>
                </ul>
              </div>
              <div>
                <div v-if="has_winner('M', winners.child)" class="stit">{{ prizeSpecial('M') }}</div>
                <ul v-if="has_winner('M', winners.child)" class="list-winner list-winner2">
                  <li v-for="(winner, index) in winners.child" :key="winner.w_id" v-if="is_winner('M', winner.z_title)" @click="openWinnerDetail(winner.w_id)">
                    <div class="thumb">
                      <v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
                      <v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
                    </div>
                    <div class="tit">{{winner.w_title}}</div>
                    <div class="company">{{winner.e_company}}</div>
                  </li>
                </ul>
              </div>
              <div>
                <div v-if="has_winner('G', winners.child)" class="stit">Grand Prix</div>
                <ul v-if="has_winner('G', winners.child)" class="list-winner list-winner2">
                  <li v-for="(winner, index) in winners.child" :key="winner.w_id" v-if="is_winner('G', winner.z_title)" @click="openWinnerDetail(winner.w_id)">
                    <div class="thumb">
                      <v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
                      <v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
                    </div>
                    <div class="tit">{{winner.w_title}}</div>
                    <div class="company">{{winner.e_company}}</div>
                  </li>
                </ul>
              </div>
              <div>
                <div v-if="has_winner('W', winners.child)" class="stit">Winner</div>
                <ul v-if="has_winner('W', winners.child)" class="list-winner list-winner2">
                  <li v-for="(winner, index) in winners.child" :key="winner.w_id" v-if="is_winner('W', winner.z_title)" @click="openWinnerDetail(winner.w_id)">
                    <div class="thumb">
                      <v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
                      <v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
                    </div>
                    <div class="tit">{{winner.w_title}}</div>
                    <div class="company">{{winner.e_company}}</div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div id="subcategory_title_top" class="subcategory_title_top">
      <div class="container container-inner container-basic">
        <div class="holder" id="subcategory_title_holder"></div>
      </div>
    </div>

    <v-dialog
      v-model="modalWinnerDetail"
      fullscreen
      hide-overlay
      open-on-focus
      scrollable
      transition="dialog-bottom-transition"
      content-class="modal-winner-detail"
    >
      <v-card :style="styledText">
        <v-btn icon dark @click="closeWinnerDetail" class="btn-close">
          <v-icon>xi-close-thin</v-icon>
        </v-btn>
        <div class="container-modal-full" v-if="winner_detail.info">
          <div class="content_area scrollable" v-scroll.self="onScrollModal">
            <div class="toptext" id="toptext">A.N.D. AWARD WINNERS</div>
            <div class="inner-content wtitle_wrap">
              <div class="wtitle" id="wtitle-top">{{winner_detail.info.w_title}}</div>
            </div>
            <div class="vspace"><div></div></div>
            <div class="visual" :style="styledBg">
              <div class="holder-img">
                <div class="img_key">
                  <v-img v-if="winner_detail.info.w_thumbnail" :src="winner_detail.info.w_thumbnail" :aspect-ratio="1470/882" @error="winner_detail.info.w_thumbnail = null"></v-img>
                  <v-img v-else :src="noimage" :aspect-ratio="1470/882" class="noimg"></v-img>
                </div>
              </div>
              <div class="inner-content">
                <div class="prize">{{prizeName(winner_detail.info.z_title)}}</div>
                <!-- <div class="category">{{winner_detail.info.c_type | categoryName}} 부문</div> -->
                <div class="category">{{winner_detail.info.cp_title}} 부문</div>
                <div class="subcategory">{{winner_detail.info.c_title}} 분야</div>
                <div class="year">{{winner_detail.info.a_id}}</div>
              </div>
            </div>
            <div class="con_aera inner-content">
              <div v-if="winner_detail.info.w_comment && !$vuetify.breakpoint.mdAndUp" class="comment pre-formatted">{{winner_detail.info.w_comment}}</div>
              <div class="description">
                <div v-if="winner_detail.info.w_comment && $vuetify.breakpoint.mdAndUp" class="comment pre-formatted">{{winner_detail.info.w_comment}}</div>
                <div class="con pre-formatted">{{winner_detail.info.w_descript}}</div>
                <v-btn depressed tile text outlined default :href="work_url" target="_blank" class="btn-type-extlink">수상작 확인하기
                  <v-icon right>xi-external-link</v-icon>
                </v-btn>
              </div>
              <ul class="img_add">
                <li v-for="(output, index) in winner_detail.output" :key="output.w_id + '-' + index">
                  <v-img v-if="output.resource" :src="output.resource" @error="output[0].resource = null"></v-img>
                  <v-img v-else :src="noimage" class="noimg"></v-img>
                </li>
              </ul>
            </div>
            <div class="info_area" :style="styledBg">
              <div class="inner-content">
                <div class="con">
                  <ul>
                    <li>
                      <div class="ilabel">개발사</div>
                      <div class="itext">{{winner_detail.info.e_company}}</div>
                    </li>
                    <li>
                      <div class="ilabel">고객사</div>
                      <div class="itext">{{a_id < 2020 ? winner_detail.info.old_client : winner_detail.info.w_client}}</div>
                    </li>
                    <li>
                      <div class="ilabel">공동개발사</div>
                      <div class="itext">{{winner_detail.info.w_coworker | notExist}}</div>
                    </li>
                    <li>
                      <div class="ilabel">제작기간</div>
                      <div class="itext">{{formatDate(winner_detail.info.w_start)}} - {{formatDate(winner_detail.info.w_end)}}</div>
                    </li>
                  </ul>
                  <!-- <v-btn depressed tile text outlined large :to="{ name: 'userAwardWinnerList', params: { award: '2019' }, query: {category: '1'}}">수상작 목록
                    <v-icon right>xi-angle-right</v-icon>
                  </v-btn> -->
                  <v-btn depressed tile text outlined large :style="styledButton" @click="closeWinnerDetail">수상작 목록</v-btn>
                  <v-tooltip v-model="tooltipCopy.share_url" top transition="slide-y-reverse-transition">
                    <template v-slot:activator="{ on }">
                      <v-btn depressed tile text large style="padding:0 10px;" @click="copyClipboard('share_url', winner_detail.info.w_id)" class="btn-type-copy">
                        <v-icon>xi-share-alt-o</v-icon>
                      </v-btn>
                    </template>
                    <span>URL이 복사되었습니다</span>
                  </v-tooltip>
                </div>
                <div class="img_bot">
                  <v-img v-if="winner_detail.info.w_thumbnail" :src="winner_detail.info.w_thumbnail" :aspect-ratio="580/348" @error="winner_detail.info.w_thumbnail = null"></v-img>
                  <v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
                </div>
              </div>
            </div>
          </div>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { TweenMax, TimelineMax } from 'gsap'
import { EventBus } from "../../../common/EventBus"

export default {
  data() {
    return {
      noimage: '/assets/img/common/noimage_600.png',
      modalWinnerDetail: false,

      current_award: [],
      a_id: '',
      w_id: '',

      categories: [],
      category_type: 1,
      drawerCategory_type: '',
      category_name: '',
      winner_all: [],
      winner_detail: [],
      work_url: '',

      subcategory_target: [],
      subcategory_target_offset: [],
      subcategory_target_content: [],
      eCounter: 0,

      year: '',
      type: 1,
      id: 1,

      tooltipCopy: {
        share_url: false,
      },
    }
  },
  computed: {
    styledBg() {
      return {
        'background-color': this.current_award.color_backg,
      };
    },
    styledBgwidthBoder() {
      return {
        'background-color': this.current_award.color_backg,
        'border-color': this.current_award.color_backg,
      };
    },
    styledTextbyBg() {
      return {
        'color': this.current_award.color_backg,
      };
    },
    styledText() {
      return {
        'color': this.current_award.color_text,
      };
    },
    styledStroke() {
      return {
        '-webkit-text-stroke-color': this.current_award.color_text,
      };
    },
    styledButton() {
      return {
        'color': this.current_award.color_bttext,
        'background-color': this.current_award.color_btbackg,
        'border-color': this.current_award.color_btline
      };
    }
  },
  mounted() {
    this.a_id = this.$route.params.award ? this.$route.params.award : 0;
    this.w_id = this.$route.query.w_id ? this.$route.query.w_id : 0;

    this.query_category = this.$route.query.category ? this.$route.query.category : 1;
    this.category_type = this.query_category;
    this.fetchItems();
    this.transition();

    if (this.w_id > 0) {
      this.openWinnerDetail(this.w_id);
    }
  },
  created() {
    EventBus.$on('change-category', (changeView, type) => {
      this.winner_all = changeView;
      this.category_type = type;
      this.get_subcategory_name();
    });
    EventBus.$on('all-category', (type) => {
      this.category_type = type;
      this.fetchItems();
    });
  },
  watch: {
    modalWinnerDetail(val) {
      const txtLnb = $('#nav-lnb');
      if (val) {
        if (this.$vuetify.breakpoint.mdAndUp != true) {
          txtLnb.addClass('d-none');
        }
      } else {
        if (this.$vuetify.breakpoint.mdAndUp != true) {
          txtLnb.removeClass('d-none');
        }
      }
    }
  },
  methods: {
    fetchItems(status) {
      this.a_id = this.$route.params.award ? this.$route.params.award : 0;

      this.$axios.get('/api/get/award/' + this.a_id).then(res => {
        this.current_award = res.data.data;
        // console.log(this.current_award);

        this.$axios.get('/api/get/category/' + this.a_id).then(res => {
          this.categories = res.data.data.data;
          // console.log(this.categories);
        });
      });

      if (status == 'init') {
        this.category_type = 1;
      }

      this.$axios.get('/api/service/winners/' + this.a_id + '/' + this.category_type).then(res => {
        let data = res.data.data;
        // console.log(data);
        this.winner_all = data.filter(obj => obj.hasOwnProperty('child'));
        // console.log(this.winner_all);
        setTimeout(() => {
          this.get_subcategory_name();
          this.$store.commit('setOffsetBottom');
        }, 0)
      }).catch(err => {
        this.winner_all = [];
      });;
    },
    transition() {
      let section_winner_list_cover_height = document.getElementById('section-winner-list-cover').offsetHeight * 1.5;
      // console.log(section_winner_list_cover_height);
      const scene_winner_list_cover_tl = new TimelineMax ()
          .add([
            TweenMax.to($('#section-winner-list-cover .txt_wrap'), 1, {transform: 'translate(-78%, -50%)'}),
          ]);
      const scene_winner_list_cover = this.$scrollmagic.scene({
        triggerElement: '#section-winner-list-cover',
        triggerHook: '0.5',
        duration: section_winner_list_cover_height
      })
        // .setPin('#section-winner-list-cover')
        .setTween(scene_winner_list_cover_tl)
        // .addIndicators({ name: 'scene_winner_list_cover' })
      this.$scrollmagic.addScene(scene_winner_list_cover);
    },
    formatDate(value) {
      return value ? this.$moment(value).format('YYYY.MM.DD') : '-';
    },
    get_winners(type) {
      this.category_type = type;
      EventBus.$emit('change-tab', type);
      this.fetchItems();
    },
    has_winner(type, list) {
      if (Number(this.a_id) < 2020 ) {
        // if (type == 'G') {
        //   return list.some(item => item.z_title == type || item.z_title == 'M' || item.z_title == 'K' || item.z_title == 'S');
        // } else {
          return list.some(item => item.z_title == type);
        // }
      } else {
        if (type == 'K') {
          return list.some(item => item.z_title == '한국방송광고진흥공사장상');
        } else if (type == 'S') {
          return list.some(item => item.z_title == '한국인터넷진흥원장상');
        } else if (type == 'M') {
          return list.some(item => item.z_title == '과학기술정보통신부장관상');
        } else if (type == 'G') {
          return list.some(item => item.z_title == 'Grand Prix');
        } else {
          return list.some(item => item.z_title == 'Winner');
        }
      }
    },
    is_winner(type, z_title) {
      if (Number(this.a_id) < 2020 ) {
        // if (type == 'G') {
        //   return (z_title == 'G' || z_title == 'M' || z_title == 'K' || z_title == 'S');
        // } else {
        //   return (z_title == 'W');
        // }
        return (z_title == type);
      } else {
        if (type == 'K') {
          return (z_title == '한국방송광고진흥공사장상');
        } else if (type == 'S') {
          return (z_title == '한국인터넷진흥원장상');
        } else if (type == 'M') {
          return (z_title == '과학기술정보통신부장관상');
        } else if (type == 'G') {
          return (z_title == 'Grand Prix');
        } else {
          return (z_title == 'Winner');
        }
      }
    },
    get_subcategory_name() {
      this.subcategory_target = document.querySelectorAll('.subcategory_wrap');
      for (let i=0; i<this.subcategory_target.length; i++) {
        // if (this.subcategory_target[i].hasChildNodes() && this.subcategory_target[i].childNodes[0].nodeName != '#comment') {
          this.subcategory_target_offset[i] = this.subcategory_target[i].getBoundingClientRect().top + this.$store.state.offsetTop;
          this.subcategory_target_content[i] = this.subcategory_target[i].getElementsByClassName('subcategory')[0].innerHTML;
        // } else {
        //   this.subcategory_target_offset[i] = 0;
        //   this.subcategory_target_content[i] = 0;
        // }
      }
    },
    openWinnerDetail(w_id) {
      if (Number(this.a_id) < 2020) {
        var api_winner_detail = '/api/service/winner/' + w_id;
      } else {
        var api_winner_detail = '/api/service/winner/' + w_id + '/' + this.a_id;
      }
      this.$axios.get(api_winner_detail).then(res => {
        this.winner_detail = res.data.data;
        // console.log('winner_detail', this.winner_detail);
        if (Number(this.a_id) < 2020) {
          this.work_url = this.winner_detail.info.old_url;
        } else {
          this.work_url = this.winner_detail.info.w_url;
        }
      });
      this.modalWinnerDetail = true;
    },
    prizeName(value) {
      if (Number(this.a_id) < 2020 ) {
        if (value == 'K') {
          return '한국방송광고진흥공사장상';
        } else if (value == 'S') {
          return '한국인터넷진흥원장상';
        } else if (value == 'M') {
          if (this.a_id == 2012) {
            return '문화체육관광부장관상';
          } else if (this.a_id == 2013 || this.a_id == 2014 || this.a_id == 2015 || this.a_id == 2016) {
            return '미래창조과학부장관상';
          } else {
            return '과학기술정보통신부장관상';
          }
        }
      } else {
        return value;
      }
    },
    closeWinnerDetail() {
      this.winner_detail = [];
      this.work_url = '';
      this.modalWinnerDetail = false;
    },
    onScroll(e) {
      let elem = document.getElementById('subcategory_title_holder');
      let win_offsetTop = this.$store.state.offsetTop;
      let target_offsetTop = this.subcategory_target_offset;

      if (target_offsetTop[0] > win_offsetTop) {
          elem.innerHTML = '';
      }
      for (let [index, item] of target_offsetTop.entries()) {
        if (item <= win_offsetTop) {
          // console.log(this.subcategory_target_content[index]);
          elem.innerHTML = this.subcategory_target_content[index];
        }
      }
    },
    onScrollModal(e) {
      let target_offsetTop = e.target.scrollTop;
      let toptext = document.getElementById('toptext');
      let elem = document.getElementById('wtitle-top');
      let elemH = elem.offsetHeight;
      let classCheck = new RegExp("(\\s|^)active(\\s|$)");
      if (this.eCounter == 0) {
        if (target_offsetTop > 140) {
          e.target.className += ' active';
          toptext.style.marginBottom = elemH+'px';
          this.eCounter++;
        }
      }
      if (this.eCounter == 1) {
        if (target_offsetTop <= 140) {
          e.target.className = e.target.className.replace(classCheck, ' ').trim();
          toptext.style.marginBottom = 0;
          this.eCounter--;
        }
      }
    },
    copyClipboard(type, w_id) {
      const container = document.querySelector('.v-dialog');
      if (type == 'share_url') {
        this.$copyText('https://naward.or.kr/archive/winners/' + this.a_id + '?w_id=' + w_id, container)
        .then((e) => {
          this.tooltipCopy.share_url = true;
          setTimeout(() => {
            this.tooltipCopy.share_url = false;
          }, 800)
        }, function (e) {
          // console.log(e)
        })
      }
    },
    prizeSpecial(val) {
      if (val == 'K') {
        return '한국방송광고진흥공사장상';
      } else if (val == 'S') {
        return '한국인터넷진흥원장상';
      } else if (val == 'M') {
        if (this.a_id == 2012) {
          return '문화체육관광부장관상';
        } else if (this.a_id == 2013 || this.a_id == 2014 || this.a_id == 2015 || this.a_id == 2016) {
          return '미래창조과학부장관상';
        } else {
          return '과학기술정보통신부장관상';
        }
      }
    }
  },
  filters: {
    // prizeName: function(value) {
    //   return value == 'M' ? 'MINISTER PRIX' : value == 'K' ? 'KOBACO PRIX' : value == 'S' ? 'KISA PRIX' : value == 'G' ? 'GRAND PRIX' : 'WINNER';
    // },
    // categoryName: function(value) {
    //   return value == '1' ? 'DIGITAL MEDIA & SERVICES' : 'DIGITAL AD & CAMPAIGNS';
    // },
    notExist: function(value) {
      return value ? value : '-';
    }
  }
}
</script>