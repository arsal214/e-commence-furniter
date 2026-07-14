{{--
    Account shell — the frame shared by my-account / edit-account / order-history
    (and wishlist, whenever it moves over).

    Renders: breadcrumb, greeting header, optional stat row, sidebar + main grid.
    All account CSS/JS lives here and is pushed @once, so a page that uses the
    shell never ships it twice.

    Props : active  which sidebar item to highlight (dashboard|edit|orders|wishlist)
            user    the signed-in user (for the avatar + greeting)
            crumb   trailing breadcrumb label
    Slots : $slot   main column
            $stats  optional stat-tile row above the grid
--}}
@props([
    'active'     => 'dashboard',
    'user',
    'heading'    => null,
    'subheading' => null,
    'crumb'      => 'Account',
])

@php
    $initials = collect(explode(' ', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

@once
@push('styles')
<style>
/* ── Account (acc-) ────────────────────────────────────────────
   Gold #bb976d is an accent tone: it fails 4.5:1 on white, so text
   and links use the darker --ac-gold-ink. */
.acc {
    --ac-gold:      #bb976d;
    --ac-gold-soft: #d4a96a;
    --ac-gold-ink:  #8a6a3f;
    --ac-surface:   #ffffff;
    --ac-bg:        #faf9f7;
    --ac-border:    rgba(187, 151, 109, .24);
    --ac-text:      #1f1a15;
    --ac-muted:     #6b6157;
    --ac-danger:    #b42318;
    --ac-field:     #ffffff;

    background:
        radial-gradient(900px 400px at 85% -5%, rgba(187, 151, 109, .13), transparent 60%),
        var(--ac-bg);
    padding: 40px 0 72px;
    font-family: 'DM Sans', 'Poppins', ui-sans-serif, system-ui, sans-serif;
    color: var(--ac-text);
}
.dark .acc {
    --ac-surface: rgba(23, 36, 48, .5);
    --ac-bg:      #0a0806;
    --ac-border:  rgba(212, 169, 106, .2);
    --ac-text:    #f3ede4;
    --ac-muted:   #a89f93;
    --ac-danger:  #ff9c94;
    --ac-gold-ink:#d4a96a;
    --ac-field:   rgba(10, 8, 6, .4);
}

.acc__wrap { max-width: 1280px; margin: 0 auto; padding: 0 20px; }

/* ── Header ────────────────────────────────────────────────── */
.acc__crumbs {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--ac-muted); margin-bottom: 18px;
}
.acc__crumbs a { color: var(--ac-muted); text-decoration: none; }
.acc__crumbs a:hover { color: var(--ac-gold-ink); }
.acc__crumbs [aria-current] { color: var(--ac-gold-ink); font-weight: 600; }

.acc__head { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 28px; }
.acc__avatar {
    display: grid; place-items: center;
    width: 60px; height: 60px; flex: none;
    border-radius: 50%;
    font-size: 20px; font-weight: 700; letter-spacing: .5px;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a, #bb976d);
    box-shadow: 0 6px 18px -6px rgba(187, 151, 109, .6);
}
.acc__hello { font-size: clamp(22px, 3vw, 28px); font-weight: 700; letter-spacing: -.3px; margin: 0 0 4px; }
.acc__sub   { font-size: 14px; color: var(--ac-muted); margin: 0; }

/* ── Stat tiles ────────────────────────────────────────────── */
.acc__stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 28px; }
@media (min-width: 900px) { .acc__stats { grid-template-columns: repeat(4, 1fr); gap: 16px; } }

.acc-stat {
    background: var(--ac-surface);
    border: 1px solid var(--ac-border);
    border-radius: 16px;
    padding: 16px 18px;
    transition: transform .2s ease, box-shadow .2s ease;
}
.acc-stat:hover { transform: translateY(-2px); box-shadow: 0 12px 28px -14px rgba(10, 8, 6, .28); }
.acc-stat__label {
    display: flex; align-items: center; gap: 7px;
    font-size: 11.5px; font-weight: 600; letter-spacing: .8px; text-transform: uppercase;
    color: var(--ac-muted); margin-bottom: 10px;
}
.acc-stat__label svg { color: var(--ac-gold); flex: none; }
.acc-stat__value {
    font-size: 26px; font-weight: 700; line-height: 1;
    font-variant-numeric: tabular-nums;
    color: var(--ac-text);
}

/* ── Layout ────────────────────────────────────────────────── */
.acc__grid { display: grid; grid-template-columns: 1fr; gap: 24px; align-items: start; }
@media (min-width: 1025px) { .acc__grid { grid-template-columns: 260px 1fr; gap: 28px; } }

/* ── Sidebar ───────────────────────────────────────────────── */
.acc-nav {
    background: var(--ac-surface);
    border: 1px solid var(--ac-border);
    border-radius: 16px;
    padding: 10px;
}
@media (min-width: 1025px) { .acc-nav { position: sticky; top: 24px; } }

.acc-nav__list { list-style: none; margin: 0; padding: 0; }

.acc-nav__link {
    display: flex; align-items: center; gap: 11px;
    width: 100%;
    min-height: 44px;
    padding: 11px 13px;
    border: 0; border-radius: 11px;
    background: transparent;
    font-size: 14.5px; font-weight: 600;
    color: var(--ac-text);
    text-align: left; text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, color .2s ease;
}
.acc-nav__link:hover { background: rgba(187, 151, 109, .1); color: var(--ac-gold-ink); }
.acc-nav__link:focus-visible { outline: 2px solid var(--ac-gold); outline-offset: 2px; }
.acc-nav__link.is-active {
    background: rgba(187, 151, 109, .14);
    color: var(--ac-gold-ink);
    box-shadow: inset 3px 0 0 var(--ac-gold);
}
.acc-nav__icon { display: inline-flex; color: currentColor; flex: none; }

.acc-nav__logout { margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--ac-border); }
.acc-nav__link--danger { color: #b42318; }
.dark .acc-nav__link--danger { color: #ff9c94; }
.acc-nav__link--danger:hover { background: rgba(180, 35, 24, .1); color: #b42318; }
.dark .acc-nav__link--danger:hover { color: #ff9c94; }

/* ── Panels ────────────────────────────────────────────────── */
.acc-panel {
    background: var(--ac-surface);
    border: 1px solid var(--ac-border);
    border-radius: 18px;
    padding: 22px;
}
@media (min-width: 640px) { .acc-panel { padding: 26px 28px; } }
.acc-panel + .acc-panel { margin-top: 20px; }

.acc-panel__head {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; flex-wrap: wrap; margin-bottom: 18px;
}
.acc-panel__title { font-size: 17px; font-weight: 700; margin: 0; color: var(--ac-text); }

.acc-link {
    font-size: 13.5px; font-weight: 600;
    color: var(--ac-gold-ink); text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
    border-radius: 4px;
}
.acc-link:hover { text-decoration: underline; text-underline-offset: 3px; }
.acc-link:focus-visible { outline: 2px solid var(--ac-gold); outline-offset: 3px; }
.acc-link svg { transition: transform .2s ease; }
.acc-link:hover svg { transform: translateX(3px); }

/* Detail rows */
.acc-detail { display: grid; gap: 14px; }
@media (min-width: 640px) { .acc-detail { grid-template-columns: 1fr 1fr; gap: 16px 24px; } }
.acc-detail__item {
    display: flex; align-items: center; gap: 11px;
    padding: 12px 14px;
    border: 1px solid var(--ac-border);
    border-radius: 12px;
    background: rgba(187, 151, 109, .05);
}
.acc-detail__item svg { color: var(--ac-gold); flex: none; }
.acc-detail__k { display: block; font-size: 11.5px; font-weight: 600; letter-spacing: .6px; text-transform: uppercase; color: var(--ac-muted); margin-bottom: 3px; }
.acc-detail__v { display: block; font-size: 14.5px; font-weight: 600; color: var(--ac-text); word-break: break-word; }

/* ── Buttons ───────────────────────────────────────────────── */
.acc-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: 44px; padding: 0 20px;
    font-size: 14px; font-weight: 700;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a 0%, #bb976d 55%, #a9855c 100%);
    border: 0; border-radius: 11px;
    text-decoration: none; cursor: pointer;
    box-shadow: 0 6px 18px -8px rgba(187, 151, 109, .6);
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s ease, opacity .2s ease;
}
.acc-btn:hover  { transform: translateY(-2px); box-shadow: 0 12px 26px -10px rgba(187, 151, 109, .7); }
.acc-btn:active { transform: translateY(0) scale(.99); }
.acc-btn:focus-visible { outline: 2px solid var(--ac-gold-ink); outline-offset: 3px; }
.acc-btn[disabled], .acc-btn[aria-busy="true"] { opacity: .7; cursor: not-allowed; transform: none; box-shadow: none; }
.acc-btn__spinner { display: none; animation: acc-spin .7s linear infinite; }
.acc-btn[aria-busy="true"] .acc-btn__spinner { display: block; }
@keyframes acc-spin { to { transform: rotate(360deg); } }

.acc-btn--ghost {
    color: var(--ac-text);
    background: transparent;
    border: 1px solid var(--ac-border);
    box-shadow: none;
}
.acc-btn--ghost:hover { background: rgba(187, 151, 109, .08); box-shadow: none; transform: none; }

/* ── Form fields ───────────────────────────────────────────── */
.acc-fieldset { border: 0; margin: 0 0 4px; padding: 0; }
.acc-fieldset + .acc-fieldset { margin-top: 26px; padding-top: 24px; border-top: 1px solid var(--ac-border); }
.acc-fieldset__legend { font-size: 15px; font-weight: 700; color: var(--ac-text); padding: 0; margin-bottom: 4px; }
.acc-fieldset__hint { font-size: 13px; color: var(--ac-muted); margin: 0 0 16px; }

.acc-form__grid { display: grid; grid-template-columns: 1fr; gap: 0 18px; }
@media (min-width: 640px) { .acc-form__grid { grid-template-columns: 1fr 1fr; } }

.acc-field { margin-bottom: 16px; }
.acc-field--full { grid-column: 1 / -1; }
.acc-field__label { display: block; font-size: 13.5px; font-weight: 600; color: var(--ac-text); margin-bottom: 7px; transition: color .2s ease; }
.acc-field:focus-within .acc-field__label { color: var(--ac-gold-ink); }
.acc-field__req { color: var(--ac-danger); margin-left: 2px; }

.acc-field__box { position: relative; display: flex; align-items: center; }
.acc-field__icon { position: absolute; left: 14px; color: var(--ac-muted); pointer-events: none; transition: color .2s ease; }
.acc-field:focus-within .acc-field__icon { color: var(--ac-gold-ink); }

.acc-field__input {
    width: 100%;
    height: 50px;              /* >= 44px touch target */
    font-size: 16px;           /* stops iOS zoom-on-focus */
    color: var(--ac-text);
    background: var(--ac-field);
    border: 1px solid var(--ac-border);
    border-radius: 12px;
    padding: 0 14px 0 44px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.acc-field__input::placeholder { color: var(--ac-muted); opacity: .75; }
.acc-field__input:hover { border-color: rgba(187, 151, 109, .5); }
.acc-field__input:focus-visible { border-color: var(--ac-gold); box-shadow: 0 0 0 4px rgba(187, 151, 109, .18); }
.acc-field__input--toggle { padding-right: 50px; }
.acc-field__input[aria-invalid="true"] { border-color: var(--ac-danger); background: rgba(180, 35, 24, .04); }
.acc-field__input[aria-invalid="true"]:focus-visible { box-shadow: 0 0 0 4px rgba(180, 35, 24, .14); }

.acc-field__toggle {
    position: absolute; right: 3px;
    display: inline-flex; align-items: center; justify-content: center;
    width: 44px; height: 44px;
    border: 0; border-radius: 10px;
    background: transparent; color: var(--ac-muted);
    cursor: pointer;
    transition: color .2s ease, background .2s ease;
}
.acc-field__toggle:hover { color: var(--ac-gold-ink); background: rgba(187, 151, 109, .1); }
.acc-field__toggle:focus-visible { outline: 2px solid var(--ac-gold); outline-offset: 2px; }
.acc-field__toggle .acc-eye-off { display: none; }
.acc-field__toggle[aria-pressed="true"] .acc-eye-on  { display: none; }
.acc-field__toggle[aria-pressed="true"] .acc-eye-off { display: block; }

.acc-field__error {
    display: none; align-items: center; gap: 5px;
    font-size: 12.5px; font-weight: 500; color: var(--ac-danger);
    margin-top: 6px;
}
.acc-field__error.is-visible { display: flex; }
.acc-field__error svg { flex: none; }

/* ── Order cards (order history) ───────────────────────────── */
.acc-ordercard {
    border: 1px solid var(--ac-border);
    border-radius: 16px;
    overflow: hidden;
    background: var(--ac-surface);
}
.acc-ordercard + .acc-ordercard { margin-top: 16px; }

.acc-ordercard__head {
    display: flex; align-items: center; justify-content: space-between;
    gap: 14px; flex-wrap: wrap;
    padding: 16px 18px;
    background: rgba(187, 151, 109, .06);
    border-bottom: 1px solid var(--ac-border);
}
.acc-ordercard__facts { display: flex; flex-wrap: wrap; gap: 14px 26px; }
.acc-ordercard__k { display: block; font-size: 10.5px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--ac-muted); margin-bottom: 4px; }
.acc-ordercard__v { display: block; font-size: 14px; font-weight: 700; color: var(--ac-text); font-variant-numeric: tabular-nums; }

.acc-ordercard__items { list-style: none; margin: 0; padding: 0; }
.acc-ordercard__item {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 18px;
}
.acc-ordercard__item + .acc-ordercard__item { border-top: 1px solid var(--ac-border); }
.acc-ordercard__thumb {
    width: 46px; height: 46px; flex: none;
    border-radius: 10px; object-fit: cover;
    background: rgba(187, 151, 109, .14);
    border: 1px solid var(--ac-border);
}
.acc-ordercard__name { font-size: 14.5px; font-weight: 600; color: var(--ac-text); margin: 0 0 3px; }
.acc-ordercard__qty  { font-size: 12.5px; color: var(--ac-muted); margin: 0; font-variant-numeric: tabular-nums; }
.acc-ordercard__line { margin-left: auto; font-size: 14.5px; font-weight: 700; color: var(--ac-text); font-variant-numeric: tabular-nums; white-space: nowrap; }

.acc-ordercard__foot {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; flex-wrap: wrap;
    padding: 13px 18px;
    border-top: 1px solid var(--ac-border);
    background: rgba(187, 151, 109, .04);
}
.acc-ordercard__track { font-size: 12.5px; color: var(--ac-muted); }
.acc-ordercard__track strong { color: var(--ac-text); font-variant-numeric: tabular-nums; }
.acc-ordercard__total { font-size: 15px; font-weight: 700; color: var(--ac-text); font-variant-numeric: tabular-nums; }

/* ── Compact order rows (dashboard) ────────────────────────── */
.acc-orders { list-style: none; margin: 0; padding: 0; display: grid; gap: 12px; }
.acc-order {
    display: grid; grid-template-columns: 1fr; gap: 12px;
    padding: 14px;
    border: 1px solid var(--ac-border);
    border-radius: 14px;
    background: rgba(187, 151, 109, .04);
    transition: border-color .2s ease, transform .2s ease;
}
.acc-order:hover { border-color: rgba(187, 151, 109, .5); transform: translateY(-1px); }
@media (min-width: 700px) { .acc-order { grid-template-columns: 1fr auto; align-items: center; gap: 16px; } }

.acc-order__main { display: flex; align-items: center; gap: 12px; min-width: 0; }
.acc-order__thumbs { display: flex; flex: none; }
.acc-order__thumb {
    width: 44px; height: 44px;
    border-radius: 10px; object-fit: cover;
    border: 2px solid var(--ac-surface);
    background: rgba(187, 151, 109, .16);
}
.acc-order__thumb + .acc-order__thumb { margin-left: -12px; }
.acc-order__more {
    display: grid; place-items: center;
    width: 44px; height: 44px; margin-left: -12px;
    border-radius: 10px;
    border: 2px solid var(--ac-surface);
    background: rgba(187, 151, 109, .16);
    font-size: 12px; font-weight: 700; color: var(--ac-gold-ink);
}
.acc-order__id { display: block; font-size: 11.5px; font-weight: 600; letter-spacing: .5px; color: var(--ac-muted); margin-bottom: 4px; font-variant-numeric: tabular-nums; }
.acc-order__name { font-size: 14.5px; font-weight: 600; color: var(--ac-text); margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.acc-order__meta { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.acc-order__total { font-size: 15.5px; font-weight: 700; color: var(--ac-text); font-variant-numeric: tabular-nums; }
.acc-order__date { font-size: 12.5px; color: var(--ac-muted); font-variant-numeric: tabular-nums; }

/* ── Status pill ───────────────────────────────────────────── */
.acc-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 100px;
    font-size: 11.5px; font-weight: 700; letter-spacing: .3px;
    white-space: nowrap;
}
.acc-pill__icon { display: inline-flex; }
.acc-pill--success  { color: #15803d; background: rgba(21, 128, 61, .1);   border: 1px solid rgba(21, 128, 61, .28); }
.acc-pill--info     { color: #1d4ed8; background: rgba(29, 78, 216, .1);   border: 1px solid rgba(29, 78, 216, .28); }
.acc-pill--progress { color: #8a6a3f; background: rgba(187, 151, 109, .14); border: 1px solid rgba(187, 151, 109, .35); }
.acc-pill--pending  { color: #92610a; background: rgba(236, 153, 29, .12);  border: 1px solid rgba(236, 153, 29, .35); }
.acc-pill--danger   { color: #b42318; background: rgba(180, 35, 24, .09);   border: 1px solid rgba(180, 35, 24, .28); }
.dark .acc-pill--success  { color: #6ee7a8; background: rgba(110, 231, 168, .1); border-color: rgba(110, 231, 168, .3); }
.dark .acc-pill--info     { color: #93b4ff; background: rgba(147, 180, 255, .1); border-color: rgba(147, 180, 255, .3); }
.dark .acc-pill--progress { color: #d4a96a; background: rgba(212, 169, 106, .12); border-color: rgba(212, 169, 106, .35); }
.dark .acc-pill--pending  { color: #f3c377; background: rgba(243, 195, 119, .12); border-color: rgba(243, 195, 119, .32); }
.dark .acc-pill--danger   { color: #ff9c94; background: rgba(255, 156, 148, .1); border-color: rgba(255, 156, 148, .3); }

/* ── Product cards (wishlist) ──────────────────────────────── */
.acc-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 16px;
}

.acc-card {
    position: relative;
    border: 1px solid var(--ac-border);
    border-radius: 16px;
    overflow: hidden;
    background: var(--ac-surface);
    display: flex;
    flex-direction: column;
    transition: border-color .2s ease, transform .2s ease, box-shadow .2s ease;
}
.acc-card:hover { border-color: rgba(187, 151, 109, .5); transform: translateY(-2px); box-shadow: 0 16px 34px -18px rgba(10, 8, 6, .35); }

.acc-card__media { position: relative; display: block; overflow: hidden; aspect-ratio: 4 / 3; background: rgba(187, 151, 109, .1); }
.acc-card__img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s ease; }
.acc-card:hover .acc-card__img { transform: scale(1.06); }
.acc-card__placeholder { display: grid; place-items: center; width: 100%; height: 100%; color: var(--ac-gold); opacity: .5; }

.acc-card__tag {
    position: absolute; top: 10px; left: 10px;
    padding: 5px 10px; border-radius: 100px;
    font-size: 11px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase;
    color: #0a0806; background: var(--ac-gold-soft);
}

/* Remove: 44px target, sits above the image with its own surface for contrast */
.acc-card__remove {
    position: absolute; top: 8px; right: 8px;
    display: grid; place-items: center;
    width: 38px; height: 38px;
    border: 0; border-radius: 50%;
    background: rgba(255, 255, 255, .92);
    color: #b42318;
    cursor: pointer;
    box-shadow: 0 4px 12px -4px rgba(10, 8, 6, .35);
    transition: background .2s ease, color .2s ease, transform .18s ease;
}
.dark .acc-card__remove { background: rgba(10, 8, 6, .8); color: #ff9c94; }
.acc-card__remove:hover { background: #b42318; color: #fff; transform: scale(1.06); }
.acc-card__remove:focus-visible { outline: 2px solid var(--ac-gold); outline-offset: 2px; }

.acc-card__body { padding: 14px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
.acc-card__name {
    font-size: 14.5px; font-weight: 600; line-height: 1.35; margin: 0;
    color: var(--ac-text);
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.acc-card__name a { color: inherit; text-decoration: none; }
.acc-card__name a:hover { color: var(--ac-gold-ink); }

.acc-card__price { display: flex; align-items: baseline; gap: 8px; margin-top: auto; }
.acc-card__now  { font-size: 16px; font-weight: 700; color: var(--ac-text); font-variant-numeric: tabular-nums; }
.acc-card__was  { font-size: 12.5px; color: var(--ac-muted); text-decoration: line-through; font-variant-numeric: tabular-nums; }

.acc-card__btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    width: 100%; min-height: 44px;
    font-size: 13.5px; font-weight: 700;
    color: #0a0806;
    background: linear-gradient(135deg, #d4a96a 0%, #bb976d 55%, #a9855c 100%);
    border: 0; border-radius: 11px;
    cursor: pointer;
    transition: transform .18s cubic-bezier(.34, 1.56, .64, 1), box-shadow .2s ease, opacity .2s ease;
}
.acc-card__btn:hover { transform: translateY(-1px); box-shadow: 0 10px 22px -10px rgba(187, 151, 109, .7); }
.acc-card__btn:focus-visible { outline: 2px solid var(--ac-gold-ink); outline-offset: 2px; }
.acc-card__btn[disabled] { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }

.acc-card__oos {
    display: inline-flex; align-items: center; justify-content: center;
    width: 100%; min-height: 44px;
    font-size: 13px; font-weight: 600;
    color: var(--ac-muted);
    border: 1px dashed var(--ac-border);
    border-radius: 11px;
}

/* ── Empty state ───────────────────────────────────────────── */
.acc-empty { text-align: center; padding: 34px 16px; }
.acc-empty__icon {
    display: grid; place-items: center;
    width: 56px; height: 56px; margin: 0 auto 14px;
    border-radius: 50%;
    background: rgba(187, 151, 109, .12);
    color: var(--ac-gold);
}
.acc-empty__title { font-size: 16px; font-weight: 700; margin: 0 0 6px; color: var(--ac-text); }
.acc-empty__text  { font-size: 14px; color: var(--ac-muted); margin: 0 0 18px; }

/* ── Banners ───────────────────────────────────────────────── */
.acc-flash {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 15px; margin-bottom: 20px;
    border-radius: 12px; font-size: 14px;
    color: #15803d;
    background: rgba(21, 128, 61, .08);
    border: 1px solid rgba(21, 128, 61, .28);
}
.dark .acc-flash { color: #6ee7a8; background: rgba(110, 231, 168, .08); border-color: rgba(110, 231, 168, .3); }

.acc-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 15px; margin-bottom: 20px;
    border-radius: 12px; font-size: 14px;
    color: var(--ac-danger);
    background: rgba(180, 35, 24, .07);
    border: 1px solid rgba(180, 35, 24, .25);
}
.dark .acc-alert { background: rgba(255, 156, 148, .08); border-color: rgba(255, 156, 148, .28); }
.acc-alert svg { flex: none; margin-top: 1px; }

.acc-sr {
    position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
    overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
}

@media (prefers-reduced-motion: reduce) {
    .acc *, .acc *::before, .acc *::after { transition-duration: .01ms !important; animation: none !important; }
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    'use strict';

    var form = document.querySelector('[data-acc-form]');
    if (!form) return;

    /* Password show/hide */
    form.querySelectorAll('[data-pw-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('aria-controls'));
            if (!input) return;
            var reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            btn.setAttribute('aria-pressed', String(reveal));
            btn.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
            input.focus({ preventScroll: true });
        });
    });

    /* Validation — rules are declared on the input, so new fields need no JS. */
    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    function messageFor(input) {
        var value = input.value.trim();
        var label = input.dataset.label || 'This field';

        if (input.required && !value) return label + ' is required.';
        if (!value) return '';
        if (input.type === 'email' && !EMAIL_RE.test(value)) {
            return 'Enter a valid email address, e.g. name@example.com';
        }
        if (input.minLength > 0 && value.length < input.minLength) {
            return label + ' must be at least ' + input.minLength + ' characters.';
        }
        if (input.dataset.match) {
            var other = document.getElementById(input.dataset.match);
            if (other && other.value !== input.value) return 'Passwords do not match.';
        }
        // Changing the password requires the current one — the server enforces
        // this too, but catching it here saves a round-trip.
        if (input.dataset.requiredWith) {
            var trigger = document.getElementById(input.dataset.requiredWith);
            if (trigger && trigger.value && !value) return label + ' is required to change your password.';
        }
        return '';
    }

    function setError(input, message) {
        var box = document.getElementById(input.id + '-error');
        if (!box) return;
        var text = box.querySelector('[data-error-text]');

        if (message) {
            input.setAttribute('aria-invalid', 'true');
            if (text) text.textContent = message;
            box.classList.add('is-visible');
        } else {
            input.removeAttribute('aria-invalid');
            box.classList.remove('is-visible');
        }
    }

    var fields = form.querySelectorAll('[data-validate]');

    fields.forEach(function (input) {
        input.addEventListener('blur', function () { setError(input, messageFor(input)); });

        input.addEventListener('input', function () {
            if (input.getAttribute('aria-invalid') === 'true' && !messageFor(input)) {
                setError(input, '');
            }
            // Editing one password can invalidate the fields that depend on it.
            form.querySelectorAll('[data-match="' + input.id + '"], [data-required-with="' + input.id + '"]').forEach(function (dep) {
                if (dep.getAttribute('aria-invalid') === 'true' || dep.value) setError(dep, messageFor(dep));
            });
        });
    });

    var button = form.querySelector('[data-acc-submit]');

    form.addEventListener('submit', function (event) {
        var firstInvalid = null;

        fields.forEach(function (input) {
            var message = messageFor(input);
            setError(input, message);
            if (message && !firstInvalid) firstInvalid = input;
        });

        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.focus();
            return;
        }

        if (button) {
            button.setAttribute('aria-busy', 'true');
            button.disabled = true;
        }
    });

    window.addEventListener('pageshow', function () {
        if (button) {
            button.removeAttribute('aria-busy');
            button.disabled = false;
        }
    });
})();
</script>
@endpush
@endonce

<div class="acc">
    <div class="acc__wrap">

        <nav class="acc__crumbs" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ url('/my-account') }}">Account</a>
            @if ($crumb && $crumb !== 'Account')
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ $crumb }}</span>
            @endif
        </nav>

        <header class="acc__head">
            <div class="acc__avatar" aria-hidden="true">{{ $initials ?: 'PG' }}</div>
            <div>
                <h1 class="acc__hello">{{ $heading ?? 'Hello, ' . (Str::before($user->name, ' ') ?: $user->name) }}</h1>
                @if ($subheading)
                    <p class="acc__sub">{{ $subheading }}</p>
                @endif
            </div>
        </header>

        {{-- No flash banner here: layouts/main.blade.php already renders
             session('success') / session('error') as a floating toast, and
             showing the same message twice reads like a bug. --}}

        @isset($stats)
            <div class="acc__stats">{{ $stats }}</div>
        @endisset

        <div class="acc__grid">
            <x-account.nav :active="$active" />
            <div>{{ $slot }}</div>
        </div>

    </div>
</div>
