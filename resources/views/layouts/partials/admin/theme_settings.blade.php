<div class="pct-c-btn">
    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout"><i
        class="ph-duotone ph-gear-six"></i></a>
  </div>
  <div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">{{ __('messages.settings') }}</h5>
      <button type="button" class="btn btn-icon btn-link-danger ms-auto" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="ti ti-x"></i>
      </button>
    </div>
    <div class="pct-body customizer-body">
      <div class="offcanvas-body py-0">
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <div class="pc-dark">
              <h6 class="mb-1">{{ __('messages.theme_mode_title') }}</h6>
              <p class="text-muted text-sm">{{ __('messages.theme_mode_desc') }}</p>
              <div class="row theme-color theme-layout">
                <div class="col-4">
                  <div class="d-grid">
                    <button class="preset-btn btn active" data-value="true" onclick="layout_change('light');"
                      data-bs-toggle="tooltip" title="{{ __('messages.theme_mode_light') }}">
                      <svg class="pc-icon text-warning">
                        <use xlink:href="#custom-sun-1"></use>
                      </svg>
                    </button>
                  </div>
                </div>
                <div class="col-4">
                  <div class="d-grid">
                    <button class="preset-btn btn" data-value="false" onclick="layout_change('dark');"
                      data-bs-toggle="tooltip" title="{{ __('messages.theme_mode_dark') }}">
                      <svg class="pc-icon">
                        <use xlink:href="#custom-moon"></use>
                      </svg>
                    </button>
                  </div>
                </div>
                <div class="col-4">
                  <div class="d-grid">
                    <button class="preset-btn btn" data-value="default" onclick="layout_change_default();"
                      data-bs-toggle="tooltip"
                      title="{{ __('messages.theme_mode_auto') }}">
                      <span class="pc-lay-icon d-flex align-items-center justify-content-center"><i
                          class="ph-duotone ph-cpu"></i></span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <h6 class="mb-1">{{ __('messages.theme_contrast_title') }}</h6>
            <p class="text-muted text-sm">{{ __('messages.theme_contrast_desc') }}</p>
            <div class="row theme-contrast">
              <div class="col-6">
                <div class="d-grid">
                  <button class="preset-btn btn" data-value="true" onclick="layout_theme_contrast_change('true');"
                    data-bs-toggle="tooltip" title="{{ __('messages.true') }}">
                    <svg class="pc-icon">
                      <use xlink:href="#custom-mask"></use>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="col-6">
                <div class="d-grid">
                  <button class="preset-btn btn active" data-value="false"
                    onclick="layout_theme_contrast_change('false');" data-bs-toggle="tooltip" title="{{ __('messages.false') }}">
                    <svg class="pc-icon">
                      <use xlink:href="#custom-mask-1-outline"></use>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </li>

          <li class="list-group-item">
            <h6 class="mb-1">{{ __('messages.theme_layout_title') }}</h6>
            <p class="text-muted text-sm">{{ __('messages.theme_layout_desc') }}</p>
            <div class="theme-main-layout d-flex align-center gap-1 w-100">
              <a href="#!" data-bs-toggle="tooltip" title="Vertical" class="active" data-value="vertical"><img
                  src="{{ asset('admin-assets') }}/images/customizer/caption-on.svg" alt="img" class="img-fluid" />
              </a>
              <a href="#!" data-bs-toggle="tooltip" title="Compact" data-value="compact"><img
                  src="{{ asset('admin-assets') }}/images/customizer/compact.svg" alt="img" class="img-fluid" />
                </a>
              <a href="#!" data-bs-toggle="tooltip" title="Tab" data-value="tab"><img
                  src="{{ asset('admin-assets') }}/images/customizer/tab.svg" alt="img" class="img-fluid" />
              </a>
            </div>
          </li>

          <li class="list-group-item">
            <div class="d-grid">
              <button class="btn btn-light-danger" id="layoutreset">
                Reset Layout
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
