@push('head-scripts')
    @vite(['resources/js/pickerjs.js'])
    @vite(['resources/sass/pickerjs.scss'])
@endpush
<div class="modal fade" id="subscribe" tabindex="-1" aria-labelledby="subscribeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="subscribeLabel"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="lead"></p>
                <form>
                    <input type="number" class="form-control hide" min="1" name="volunteer_id"
                           id="volunteer_id" aria-label="volunteer_id">
                    <div class="form-floating py-1">
                        <input type="number" class="form-control" min="1" name="amount"
                               id="amount" value="33">
                        <label for="amount">
                            Сума щоденного донату, грн.
                        </label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="number" class="form-control" name="sum"
                               id="sum" value="990" disabled>
                        <label for="sum">
                            Прогноз витрат за 30 днів
                        </label>
                    </div>
                    <div class="form-floating py-1">
                        <input type="text" class="form-control js-time-picker" name="scheduled_at"
                               id="scheduled_at" value="10:00">
                        <label for="scheduled_at">
                            Час нагадування донату від бота
                        </label>
                        <div class="js-mini-picker-container"></div>
                    </div>
                    <div class="form-check form-switch d-flex justify-content-between mt-3">
                        <input class="form-check-input" type="checkbox" value="" id="use_random">
                        <label class="form-check-label" for="use_random">
                            Якщо немає відкритого збору у волонтера - присилати рандомно відкритий збір інших волонтерів
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary justify-content-evenly"
                        data-bs-dismiss="modal">
                    Закрити
                </button>
                <button id="subscribe-del" type="button" class="btn btn-danger">Видалити</button>
                <button id="subscribe-action" type="button" class="btn btn-primary"></button>
            </div>
        </div>
    </div>
</div>
<script type="module">
    let subscribeAction = $('#subscribe-action');
    $('#subscribe-del').on('click', event => {
        event.preventDefault();
        $.ajax({
            url: subscribeAction.attr('data-del-url'),
            type: "DELETE",
            data: {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: () => {
                location.reload();
            },
            error: data => {
                let empty = $("<a>");
                toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                empty.click();
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
        });
        return false;
    });
    subscribeAction.on('click', event => {
        event.preventDefault();
        $.ajax({
            url: subscribeAction.attr('data-url'),
            type: subscribeAction.attr('data-action-type'),
            data: {
                user_id: {{ auth()->user()->getId() }},
                volunteer_id: $('#volunteer_id').val(),
                amount: $('#amount').val(),
                scheduled_at: $('#scheduled_at').val(),
                use_random: $('#use_random').is(':checked') ? '1' : '0',
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: () => {
                location.reload();
            },
            error: data => {
                let empty = $("<a>");
                toast(JSON.parse(data.responseText).message, empty, 'text-bg-danger');
                empty.click();
                $('meta[name="csrf-token"]').attr('content', data.csrf);
            },
        });
        return false;
    });
    $('#amount').on('change input', () => {
        $('#sum').val($('#amount').val() * 30);
    });

    let subscribe = document.getElementById('subscribe');
    let picker = null;
    subscribe.addEventListener('show.bs.modal', event => {
        let button = event.relatedTarget;
        subscribe.querySelector('#volunteer_id').value = button.getAttribute('data-bs-volunteer-id');
        subscribe.querySelector('#amount').value = button.getAttribute('data-bs-amount');
        subscribe.querySelector('#sum').value = button.getAttribute('data-bs-sum');
        let time = button.getAttribute('data-bs-scheduled-at');
        subscribe.querySelector('#scheduled_at').value = time;
        if (picker) {
            picker.destroy();
        }
        let isNew = button.getAttribute('data-bs-update') === '0';
        let btnText = isNew ? 'Підписатися' : 'Оновити';
        let btnClass = isNew ? 'btn-primary' : 'btn-warning';
        let actionType = isNew ? 'POST' : 'PATCH';
        if (isNew) {
            $('#subscribe-del').hide();
        } else {
            $('#subscribe-del').show();
        }
        subscribe.querySelector('#subscribe-action').textContent = btnText;
        subscribe.querySelector('#subscribe-action').classList.remove('btn-primary');
        subscribe.querySelector('#subscribe-action').classList.remove('btn-warning');
        subscribe.querySelector('#subscribe-action').classList.add(btnClass);
        subscribe.querySelector('#subscribe-del').classList.remove('hide');
        subscribe.querySelector('#subscribe-action').setAttribute('data-del-url', button.getAttribute('data-bs-del-url'));
        subscribe.querySelector('#subscribe-action').setAttribute('data-url', button.getAttribute('data-bs-url'));
        subscribe.querySelector('#subscribe-action').setAttribute('data-action-type', actionType);
        picker = new Picker(document.getElementById('scheduled_at'), {
            container: '.js-mini-picker-container',
            format: 'HH:mm',
            setDate: time,
            controls: true,
            inline: true,
            rows: 5,
        });
        if (button.getAttribute('data-bs-use-random').toString() === '1') {
            subscribe.querySelector('#use_random').setAttribute('checked', 'checked');
        }
        let volunteerKey = button.getAttribute('data-bs-volunteer-key');
        let actionTypeTxtTitle = isNew ? 'Підписатися' : 'Редагувати підписку';
        let actionTypeTxtContent1 = isNew ? 'Ви хочете стати серійним донатером' : 'Ви серійний донатер/ка';
        let actionTypeTxtContent2 = isNew ? ' буде бачити' : ' бачить';
        let actionTypeTxtContent3 = isNew ? 'розраховувати' : 'розраховує';
        let actionTypeTxtContent4 = isNew ? 'вибрати' : 'змінити';
        subscribe.querySelector('.modal-title').textContent = actionTypeTxtTitle + ' на @' + volunteerKey;
        subscribe.querySelector('.modal-body p.lead').textContent = actionTypeTxtContent1 + ' для волонтера @' +
            volunteerKey + '. ' + button.getAttribute('data-bs-volunteer-name') +
            actionTypeTxtContent2 + ' вашу підписку та ' + actionTypeTxtContent3 +
            ' на донат згідно умов, які ви зараз зможете ' + actionTypeTxtContent4 +
            '. Налаштування всіх ваших підписок ви можете змінити на сторінці Вашого профілю.';
    })
</script>
