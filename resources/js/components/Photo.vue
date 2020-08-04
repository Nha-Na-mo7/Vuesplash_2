<template>
  <div class="photo">
    <figure class="photo__wrapper">
      <img
          :src="item.url"
          :alt="`Photo by ${item.owner.name}`"
          class="photo__image"
      >
    </figure>
    <RouterLink
        class="photo__overlay"
        :to="`/photos/${item.id}`"
        :title="`View the photo by ${item.owner.name}`"
    >
      <div class="photo__controls">

        <!--いいね-->
        <button
            class="photo__action photo__action--like"
            :class="{ 'photo__action--liked': item.liked_by_user }"
            title="Like photo"
            @click.prevent="like"
        >
          <i class="icon ion-md-heart"></i>{{ item.likes_count }}
        </button>

        <!--ダウンロードボタン-->
        <a
            class="photo__action"
            title="Download Photo"
            @click.stop
            :href="`/photos/${item.id}/download`"
        >
          <i class="icon iom-md-arror-round-down"></i>
        </a>

      </div>

      <!--投稿者名-->
      <div class="photo__username">
        {{ item.owner.name }}
      </div>

    </RouterLink>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  methods: {
    like() {
      this.$emit('like', {
        id: this.item.id,
        liked: this.item.liked_by_user
      })
    }
  }
}
</script>