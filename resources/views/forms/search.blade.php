<form role="search" method="get" class="search-form" action="{!! esc_url(home_url('/')) !!}">
  <label>
    <span class="sr-only">
      {{ _x('Search for:', 'label', 'sage') }}
    </span>

    <input
      type="search"
      placeholder="{{ html_entity_decode(_x('Search &hellip;', 'placeholder', 'sage'), ENT_QUOTES | ENT_HTML5, 'UTF-8') }}"
      value="{{ function_exists('fds_clean_string') ? fds_clean_string(get_search_query(false)) : get_search_query(false) }}"
      name="s"
    >
  </label>

  <button>{{ _x('Search', 'submit button', 'sage') }}</button>
</form>
