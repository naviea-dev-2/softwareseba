<div class="row">
    @foreach ($productAttributeSets as $attributeSet)
        <div class="col-md-4 col-sm-6">
            <div class="form-group mb-3">
                <label
                    class="text-title-field required"
                    for="attribute-{{ $attributeSet->slug }}"
                >{{ $attributeSet->title }}</label>
                @php
                    $selected = $productVariationsInfo ? $productVariationsInfo->firstWhere('attribute_set_id', $attributeSet->id) : null;


                @endphp
                <select id="attribute-{{ $attributeSet->slug }}" name="attribute_sets[{{ $attributeSet->id }}]" class="chosen-select form-control" data-id="{{ $attributeSet->id }}">
                     @if ($selected )
                        <option selected value="{{ $selected->id }}">
                            {{$selected->title }}
                        </option>
                    @else
                        <option selected value="">
                            {{ ("Select") }}
                        </option>
                    @endif
                    @foreach($attributeSet->attributes as $row)
                        @if ($selected && $selected->id == $row->id )

                            @php
                                continue;
                            @endphp
                        @endif
                    <option value="{{ $row->id }}">
                        {{ $row->title }}
                    </option>
                    @endforeach
                </select>
                {{-- {!! Form::customSelect(
                    'attribute_sets[' . $attributeSet->id . ']',
                    $selected,
                    Arr::first(array_keys($selected)),
                    [
                        'id' => 'attribute-' . $attributeSet->slug,
                        'class' => 'select2-attributes select-search-full',
                        'data-id' => $attributeSet->id,
                    ],
                ) !!} --}}
            </div>
        </div>
    @endforeach
</div>
