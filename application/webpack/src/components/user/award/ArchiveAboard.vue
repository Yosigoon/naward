<template>
  <div class="container container-full-width" :style="styledText">
    <div class="container container-basic container-first">
      <div class="title_area">
        <h1 class="title-1 title-winner title-archive"><span :style="styledStroke">아카이브</span><br>토크샤워</h1>
        <div class="title-sub-1 title-winner" :style="styledText">앤어워드를 함께한 연사 분들의 열띤 강연과<br>행사장의 분위기를 느껴보세요</div>
      </div>
    </div>

    <section class="section section-winner-main section-winner-category section-winner-aboard">
      <div class="container container-inner container-basic">
        <div class="winner_wrap">
          <div class="tit">강연</div>
          <ul v-if="speechs.length" class="list-winner">
            <li v-for="(speech, index) in speechs" :key="speech.ab_id" @click="open_media('video', speech.ab_contents)">
              <div class="thumb"><v-img :src="speech.ab_thumb" :aspect-ratio="580/340"></v-img></div>
              <div class="tit">{{speech.ab_title}}</div>
              <div class="company">{{speech.ab_dsc}}</div>
            </li>
          </ul>
          <div v-else class="nodata"><span>아직 게시물이 없습니다</span></div>
        </div>
        <div class="winner_wrap">
          <div class="tit">갤러리</div>
          <ul v-if="gallerys.length" class="list-winner">
            <li v-for="(gallery, index) in gallerys" :key="gallery.ab_id" @click="open_media('image', gallery.ab_thumb)">
              <div class="thumb"><v-img :src="gallery.ab_thumb" :aspect-ratio="580/340"></v-img></div>
              <div class="tit">{{gallery.ab_title}}</div>
            </li>
          </ul>
          <div v-else class="nodata"><span>아직 게시물이 없습니다</span></div>
        </div>
      </div>
    </section>

    <v-dialog
      v-model="modalMedia"
      overlay-color="rgba(0,0,0)"
      overlay-opacity="0.6"
      content-class="modal-media"
      max-width="960px"
      persistent
    >
      <v-card>
        <v-card-text>
          <vue-plyr ref="plyr" :key="video_url" v-if="mediaType == 'video'" :options="{autoplay: true}">
            <div class="plyr__video-embed">
              <iframe
                :src="video_url"
                allowfullscreen allowtransparency allow="autoplay">
              </iframe>
            </div>
          </vue-plyr>
          <v-img v-if="mediaType == 'image'"
            :src="image_url"
            style="width:auto; height:auto;">
          </v-img>
        </v-card-text>
        <div class="btn_wrap">
          <v-btn depressed icon @click="close_media" class="btn-close">
            <v-icon>xi-close</v-icon>
          </v-btn>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      current_award: [],
      a_id: '',
      speechs: [],
      gallerys: [],

      modalMedia: false,
      mediaType: '',
      video_url: '',
      image_url: '',
    }
  },
  computed: {
    styledBg() {
      return {
        'background-color': this.current_award.color_backg,
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
    },
    player() {
      return this.$refs.plyr.player;
    }
  },
  mounted() {
    // this.fetchItems();
  },
  methods: {
    fetchItems() {
      this.a_id = this.$route.params.award ? this.$route.params.award : 0;
      this.speechs = [];
      this.gallerys = [];

      this.$axios.get('/api/get/award/' + this.a_id).then(res => {
        this.current_award = res.data.data;
        // console.log(this.current_award);
      });

      this.$axios.get('/api/service/speech/' + this.a_id).then(res => {
        let speechs_all = res.data.data;
        // console.log(speechs_all);
        for (let speech of speechs_all) {
          if (speech.ab_status == 1) {
            this.speechs.push(speech);
          }
        }
        // console.log(this.speechs);
      }).catch(err => {
        this.speechs = [];
      });

      this.$axios.get('/api/service/gallery/' + this.a_id).then(res => {
        let gallerys_all = res.data.data;
        // console.log(gallerys_all);
        for (let gallery of gallerys_all) {
          if (gallery.ab_status == 1) {
            this.gallerys.push(gallery);
          }
        }
        // console.log(this.gallerys);
      }).catch(err => {
        this.gallerys = [];
      });;
    },
    open_media(type, content) {
      this.mediaType = type;
      if (type == 'video') {
        this.video_url = content + '?iv_load_policy=3&modestbranding=1&playsinline=1&showinfo=0&rel=0&enablejsapi=1';
      } else if (type == 'image') {
        this.image_url = content;
      }
      this.modalMedia = true;
    },
    close_media() {
      this.video_url = '';
      this.image_url = '';
      this.modalMedia = false;
    }
  }
}
</script>
