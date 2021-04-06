<template>
	<v-container fluid class="container-full-width" :style="styledText">
		<section class="section full section-winner-main section-winner-cover">
			<div class="content_wrap">
				<div class="tit">{{a_id}} <span :style="styledStroke">앤어워드 수상작</span></div>
				<div class="stit">영광을 함께한 앤어워드 수상작을 확인해 보세요</div>
			</div>
		</section>

		<section class="section section-winner-main section-winner-top">
			<div v-if="winner_top_m.length" class="winner-top">
				<div class="prize">
					<div class="tit">과학기술정보통신부장관상</div>
					<div class="logo"><img src="/assets/img/winner/prize-1.png"></div>
				</div>
				<div v-for="(winner, index) in winner_top_m" :key="winner.w_id +'-'+ index" class="winner_wrap winner-top-left" :class="index % 2 == 0 ? 'winner-top-left' : 'winner-top-right'">
					<div class="inner-winner">
						<div class="thumb" @click="openWinnerDetail(winner.w_id)">
							<v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" @error="winner.w_thumbnail = null"></v-img>
							<v-img v-else :src="noimage" class="noimg"></v-img>
						</div>
						<div class="dsc" :style="styledBg">
							<div class="ctop">
								<div class="name" @click="openWinnerDetail(winner.w_id)">{{winner.w_title}}</div>
								<div class="category">{{winner.cp_title}}<br>{{winner.c_title}}</div>
							</div>
							<div class="cbot">
								<div class="company"><div class="clabel">개발사</div><div class="name">{{winner.e_company}}</div></div>
								<div class="company"><div class="clabel">고객사</div><div class="name">{{a_id < 2020 ? winner.old_client : winner.w_client}}</div></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div v-if="winner_top_k.length" class="winner_wrap winner-single winner-left">
				<div v-for="(winner, index) in winner_top_k" :key="winner.w_id +'-'+ index" class="inner-winner">
					<div class="prize">
						<div class="tit">한국방송광고진흥공사장상</div>
						<div class="logo"><img src="/assets/img/winner/prize-2.png"></div>
					</div>
					<div class="dsc" :style="styledBg">
						<div class="name" @click="openWinnerDetail(winner.w_id)">{{winner.w_title}}</div>
						<div class="category">{{winner.cp_title}}<br>{{winner.c_title}}</div>
					</div>
					<div class="thumb" @click="openWinnerDetail(winner.w_id)">
						<v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" @error="winner.w_thumbnail = null"></v-img>
						<v-img v-else :src="noimage" class="noimg"></v-img>
					</div>
				</div>
			</div>
			<div v-if="winner_top_s.length" class="winner_wrap winner-single winner-right">
				<div v-for="(winner, index) in winner_top_s" :key="winner.w_id +'-'+ index" class="inner-winner">
					<div class="prize">
						<div class="tit">한국인터넷진흥원장상</div>
						<!-- <div class="logo"><img src="/assets/img/winner/prize-3.png"></div> -->
						<div class="logo logo-2"><img src="/assets/img/about/sponsor-2n.png"></div>
					</div>
					<div class="dsc" :style="styledBg">
						<div class="name" @click="openWinnerDetail(winner.w_id)">{{winner.w_title}}</div>
						<div class="category">{{winner.cp_title}}<br>{{winner.c_title}}</div>
					</div>
					<div class="thumb pos-right" @click="openWinnerDetail(winner.w_id)">
						<v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" @error="winner.w_thumbnail = null"></v-img>
						<v-img v-else :src="noimage" class="noimg"></v-img>
					</div>
				</div>
			</div>
		</section>

		<section class="section section-winner-main section-winner-category section-winner-category-cover">
			<div class="content_wrap">
				<div class="tit">디지털 미디어 & 서비스 부문</div>
				<v-btn depressed tile text outlined large :to="{ name: 'userAwardWinnerList', params: { award: a_id }, query: {category: '1'}}" class="btn-type-angle" :style="styledButton">수상작 확인하기
					<v-icon right>xi-angle-right</v-icon>
				</v-btn>
			</div>
		</section>

		<section class="section section-winner-main section-winner-category">
			<div class="container container-inner container-basic">
				<div class="winner_wrap">
					<div class="tit">Grand Prix</div>
					<ul class="list-winner">
						<li v-for="(winner, index) in winner_1g" :key="winner.w_id +'-'+ index" @click="openWinnerDetail(winner.w_id)">
							<div class="thumb">
								<v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
								<v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
							</div>
							<div class="tit">{{winner.w_title}}</div>
							<div class="company">{{winner.e_company}}</div>
						</li>
					</ul>
					<div class="tit">Winner</div>
					<ul class="list-winner">
						<li v-for="(winner, index) in winner_1w" :key="winner.w_id +'-'+ index" @click="openWinnerDetail(winner.w_id)">
							<div class="thumb">
								<v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
								<v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
							</div>
							<div class="tit">{{winner.w_title}}</div>
							<div class="company">{{winner.e_company}}</div>
						</li>
					</ul>
				</div>
				<div class="btn_area">
					<v-btn depressed tile text outlined default :to="{ name: 'userAwardWinnerList', params: { award: a_id }, query: {category: '1'}}" class="btn-type-angle" :style="styledButton">전체보기
						<v-icon right>xi-angle-right</v-icon>
					</v-btn>
				</div>
			</div>
		</section>

		<section class="section section-winner-main section-winner-category section-winner-category-cover">
			<div class="content_wrap">
				<div class="tit">디지털 광고 & 캠페인 부문</div>
				<v-btn depressed tile text outlined large :to="{ name: 'userAwardWinnerList', params: { award: a_id }, query: {category: '2'}}" class="btn-type-angle" :style="styledButton">수상작 확인하기
					<v-icon right>xi-angle-right</v-icon>
				</v-btn>
			</div>
		</section>

		<section class="section section-winner-main section-winner-category">
			<div class="container container-inner container-basic">
				<div class="winner_wrap">
					<div class="tit">Grand Prix</div>
					<ul class="list-winner">
						<li v-for="(winner, index) in winner_2g" :key="winner.w_id +'-'+ index" @click="openWinnerDetail(winner.w_id)">
							<div class="thumb">
								<v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
								<v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
							</div>
							<div class="tit">{{winner.w_title}}</div>
							<div class="company">{{winner.e_company}}</div>
						</li>
					</ul>
					<div class="tit">Winner</div>
					<ul class="list-winner">
						<li v-for="(winner, index) in winner_2w" :key="winner.w_id +'-'+ index" @click="openWinnerDetail(winner.w_id)">
							<div class="thumb">
								<v-img v-if="winner.w_thumbnail" :src="winner.w_thumbnail" :aspect-ratio="580/348" @error="winner.w_thumbnail = null"></v-img>
								<v-img v-else :src="noimage" :aspect-ratio="580/348" class="noimg"></v-img>
							</div>
							<div class="tit">{{winner.w_title}}</div>
							<div class="company">{{winner.e_company}}</div>
						</li>
					</ul>
				</div>
				<div class="btn_area">
					<v-btn depressed tile text outlined default :to="{ name: 'userAwardWinnerList', params: { award: a_id }, query: {category: '2'}}" class="btn-type-angle" :style="styledButton">전체보기
						<v-icon right>xi-angle-right</v-icon>
					</v-btn>
				</div>
			</div>
		</section>

		<section class="section section-winner-main section-winner-category section-winner-category-cover">
			<div class="content_wrap">
				<div class="tit">토크샤워</div>
				<v-btn depressed tile text outlined large :to="{ name: 'userAboardList', params: { award: a_id }}" class="btn-type-angle" :style="styledButton">강연 감상하기
					<v-icon right>xi-angle-right</v-icon>
				</v-btn>
			</div>
		</section>

		<section class="section section-winner-main section-winner-category section-winner-aboard">
			<div class="container container-inner container-basic">
				<div class="winner_wrap">
					<div class="tit">강연</div>
					<ul v-if="speechs.length" class="list-winner">
						<li v-for="(speech, index) in speechs" :key="speech.ab_id" @click="open_media('video', speech.ab_contents)">
							<div class="thumb">
								<v-img v-if="speech.ab_thumb" :src="speech.ab_thumb" :aspect-ratio="580/340" @error="speech.ab_thumb = null"></v-img>
								<v-img v-else :src="noimage" :aspect-ratio="580/340" class="noimg"></v-img>
							</div>
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
							<div class="thumb">
								<v-img v-if="gallery.ab_thumb" :src="gallery.ab_thumb" :aspect-ratio="580/340" @error="speech.ab_thumb = null"></v-img>
								<v-img v-else :src="noimage" :aspect-ratio="580/340" class="noimg"></v-img>
							</div>
							<div class="tit">{{gallery.ab_title}}</div>
						</li>
					</ul>
					<div v-else class="nodata"><span>아직 게시물이 없습니다</span></div>
				</div>

				<div class="btn_area mt-10">
					<v-btn depressed tile text outlined default :to="{ name: 'userAboardList', params: { award: a_id }}" class="btn-type-angle" :style="styledButton">전체보기
						<v-icon right>xi-angle-right</v-icon>
					</v-btn>
				</div>
			</div>
		</section>

		<v-dialog
				v-model="modalWinnerDetail"
				fullscreen
				hide-overlay
				open-on-focus
				scrollable
				transition="dialog-bottom-transition"
				content-class="modal-winner-detail"
		>
			<v-card>
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
						<div class="visual">
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
						<div class="info_area">
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
									<v-btn depressed tile text outlined large @click="closeWinnerDetail">수상작 목록</v-btn>
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
	</v-container>
</template>

<script>
export default {
	data() {
		return {
			noimage: '/assets/img/common/noimage_600.png',
			modalWinnerDetail: false,

			modalMedia: false,
			mediaType: '',
			video_url: '',
			image_url: '',

			current_award: [],
			a_id: 0,
			winner_top: [],
			winner_top_m: [],
			winner_top_k: [],
			winner_top_s: [],
			winner_1g: [],
			winner_1w: [],
			winner_2g: [],
			winner_2w: [],
			speechs: [],
			gallerys: [],

			winner_detail: [],
			work_url: '',
			work_client: '',
			eCounter: 0,
		}
	},
	methods: {
		formatDate(value) {
			return value ? this.$moment(value).format('YYYY.MM.DD') : '-';
		},
		openWinnerDetail(w_id) {
			if (Number(this.a_id) < 2020) {
				var api_winner_detail = '/api/service/winner/' + w_id;
			} else {
				var api_winner_detail = '/api/service/winner/' + w_id + '/' + this.a_id;
			}
			this.$axios.get(api_winner_detail).then(res => {
				this.winner_detail = res.data.data;
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
				return value == 'M' ? 'MINISTER PRIX' : value == 'K' ? 'KOBACO PRIX' : value == 'S' ? 'KISA PRIX' : value == 'G' ? 'GRAND PRIX' : 'WINNER';
			} else {
				return value;
			}
		},
		closeWinnerDetail() {
			this.winner_detail = [];
			this.modalWinnerDetail = false;
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
		},
		onScrollModal(e) {
			let target_offsetTop = e.target.scrollTop;
			let toptext = document.getElementById('toptext');
			let elem = document.getElementById('wtitle-top');
			let elemH = elem.offsetHeight;
			let classCheck = new RegExp("(\\s|^)active(\\s|$)");
			if (this.eCounter == 0) {
				if (target_offsetTop > 160) {
					e.target.className += ' active';
					toptext.style.marginBottom = elemH+'px';
					this.eCounter++;
				}
			}
			if (this.eCounter == 1) {
				if (target_offsetTop <= 160) {
					e.target.className = e.target.className.replace(classCheck, ' ').trim();
					toptext.style.marginBottom = 0;
					this.eCounter--;
				}
			}
		}
	},
	filters: {
		notExist: function(value) {
			return value ? value : '-';
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
		}
	},
	mounted() {
		this.a_id = this.$route.params.archive ? this.$route.params.archive : 0;

		this.$axios.get('/api/get/award/' + this.a_id).then(res => {
			this.current_award = res.data.data;
		});

		this.$axios.get('/api/service/winner_received/' + this.a_id).then(res => {
			const winner_top_result = res.data.data;
			for (let [index, winner] of winner_top_result.entries()) {
				this.$axios.get('/api/get/category_info_only/' + winner.cp_id).then(res => {
					const category_info = res.data.data.data;
					if (category_info) {
						this.$set(winner_top_result[index], 'cp_title', category_info[0].c_title);
					}
				});
			};
			this.winner_top = winner_top_result;
			if (Number(this.a_id) < 2020) {
				this.winner_top_m = this.winner_top.filter(i => i.old_winnerType === 'M');
				this.winner_top_k = this.winner_top.filter(i => i.old_winnerType === 'K');
				this.winner_top_s = this.winner_top.filter(i => i.old_winnerType === 'S');
			} else {
				this.winner_top_m = this.winner_top.filter(i => i.z_title === '과학기술정보통신부장관상');
				this.winner_top_k = this.winner_top.filter(i => i.z_title === '한국방송광고진흥공사장상');
				this.winner_top_s = this.winner_top.filter(i => i.z_title === '한국인터넷진흥원장상');
			}
		});
		this.$axios.get('/api/service/winner_main/' + this.a_id + '/G/1').then(res => {
			this.winner_1g = res.data.data;
		});
		this.$axios.get('/api/service/winner_main/' + this.a_id + '/W/1').then(res => {
			this.winner_1w = res.data.data;
		});
		this.$axios.get('/api/service/winner_main/' + this.a_id + '/G/2').then(res => {
			this.winner_2g = res.data.data;
		});
		this.$axios.get('/api/service/winner_main/' + this.a_id + '/W/2').then(res => {
			this.winner_2w = res.data.data;
		});

		this.$axios.get('/api/service/speech/' + this.a_id).then(res => {
			let speechs_all = res.data.data;
			for (let speech of speechs_all) {
				if (speech.ab_status == 1) {
					this.speechs.push(speech);
				}
			}
		});


		this.$axios.get('/api/service/gallery/' + this.a_id).then(res => {
			let gallerys_all = res.data.data;
			for (let gallery of gallerys_all) {
				if (gallery.ab_status == 1) {
					this.gallerys.push(gallery);
				}
			}
		});

		// this.$axios.get('/api/service/archive').then(res => {
		// 	award_global = res.data.data;
		// 	console.log(award_global);
		// });
	}
}
</script>

<style>

</style>
