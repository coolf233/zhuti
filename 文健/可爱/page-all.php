<?php
/**
 * 文章归档
 * @package custom
 */

$this->need('base/head.php');
$this->need('base/navbar.php');
?>
<script src="<?= StaticPath . 'js/echarts.min.js'; ?>"></script>
<div class="row justify-content-center <?= Ctx::SidebarArray()[0] ?>">
    <?= Ctx::Sidebar($this) ?>
    <div id="content" class="col-lg-9">
        <section class="post-ctx">
            <?=Ctx::HeroTitleNoImg($this)?>
            <div class="row mb-4">
                <div class="h4 font-weight-bold">总览</div>
                <div class="col-md-4">
                    <div class="card-counter info">
                        <svg class="icon" aria-hidden="true" id="darkMode">
                            <use xlink:href="#wenzhang"></use>
                        </svg>
                        <span class="count-numbers"><?=Ctx::getPostNum()?></span>
                        <span class="count-name">文章</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-counter primary">
                        <svg class="icon" aria-hidden="true" id="darkMode">
                            <use xlink:href="#fenlei1"></use>
                        </svg>
                        <span class="count-numbers"><?=Ctx::getTagNum()?></span>
                        <span class="count-name">分类</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-counter success">
                        <svg class="icon" aria-hidden="true" id="darkMode">
                            <use xlink:href="#pinglun"></use>
                        </svg>
                        <span class="count-numbers"><?=Ctx::getCommentsNum()?></span>
                        <span class="count-name">评论</span>
                    </div>
                </div>
            </div>
            <div class="h4 font-weight-bold">统计</div>
            <div id="echarts-tags"></div>
            <div id="echarts-post"></div>
            <div id="echarts-sort"></div>
            <script type="text/javascript">
                function echartsInit() {
                    const Tags = echarts.init(document.getElementById('echarts-tags'), null, {renderer: 'svg'});
                    const Sort = echarts.init(document.getElementById('echarts-sort'), null, {renderer: 'svg'});
                    const Post = echarts.init(document.getElementById('echarts-post'), null, {renderer: 'svg'});
                    let tagsOption = {
                        title: {
                            text: '文章标签统计',
                            top: 2,
                            x: 'center'
                        },
                        grid: {
                            position: 'center',
                        },
                        tooltip: {},
                        xAxis: [
                            {
                                type: 'category',
                                data: <?=json_encode(Ctx::topTags($this)[0])?>,
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value',
                                max: <?=max(Ctx::topTags($this)[1]) + 2?>
                            }
                        ],
                        series: [
                            {
                                type: 'bar',
                                color: ['var(--bs-info_opacity_8)'],
                                barWidth: 18,
                                data: <?=json_encode(Ctx::topTags($this)[1])?>,
                                markPoint: {
                                    symbolSize: 45,
                                    data: [{
                                        type: 'max',
                                        itemStyle: {color: ['var(--bs-success)']},
                                        name: '最多'
                                    }, {
                                        type: 'min',
                                        itemStyle: {color: ['var(--bs-danger)']},
                                        name: '最少'
                                    }],
                                },
                                markLine: {
                                    itemStyle: {color: ['var(--bs-indigo)']},
                                    data: [
                                        {type: 'average', name: '平均值'}
                                    ]
                                }
                            }
                        ]
                    };
                    let sortOption = {
                        title: {
                            left: 'center',
                            text: '文章分类统计',
                        },
                        tooltip: {},
                        radar: {
                            name: {
                                textStyle: {
                                    color: '#3C4858'
                                }
                            },
                            indicator: <?=json_encode(Ctx::topSort($this)[0])?>,
                            nameGap: 5,
                            center: ['50%', '55%'],
                            radius: '66%'
                        },
                        series: [{
                            type: 'radar',
                            color: ['var(--bs-success_opacity_8)'],
                            itemStyle: {normal: {areaStyle: {type: 'default'}}},
                            data: [
                                {
                                    value: <?=json_encode(Ctx::topSort($this)[1])?>,
                                    name: '发布文章数'
                                }
                            ]
                        }]
                    };
                    let postOption = {
                        title: {
                            text: '文章发布统计图',
                            top: 0,
                            x: 'center'
                        },
                        tooltip: {},
                        xAxis: {
                            type: 'category',
                            data: <?=json_encode(Ctx::postCount($this)[0])?>
                        },
                        yAxis: {
                            type: 'value',
                            max: <?=max(Ctx::postCount($this)[1]) + 2?>
                        },
                        series: [
                            {
                                name: '发布文章数',
                                type: 'line',
                                color: ['#6772e5'],
                                data: <?=json_encode(Ctx::postCount($this)[1])?>,
                                markPoint: {
                                    symbolSize: 45,
                                    color: ['#fa755a', '#3ecf8e', '#82d3f4'],
                                    data: [{
                                        type: 'max',
                                        itemStyle: {color: ['#3ecf8e']},
                                        name: '最多'
                                    }, {
                                        type: 'min',
                                        itemStyle: {color: ['#fa755a']},
                                        name: '最少'
                                    }]
                                },
                                markLine: {
                                    itemStyle: {color: ['#ab47bc']},
                                    data: [
                                        {type: 'average', name: '平均值'}
                                    ]
                                }
                            }
                        ]
                    };
                    Tags.setOption(tagsOption);
                    Sort.setOption(sortOption);
                    Post.setOption(postOption);
                }
                echartsInit();
            </script>
            <?php $archives = Ctx::archives($this);
            foreach ($archives as $year => $posts) : ?>
            <div class="archives-list">
                <div class="h5 ml-2"><?php echo $year; ?></div>
                <div class="d-flex flex-column">
                    <?php foreach ($posts as $created => $post) : ?>
                        <a href="<?php echo $post['permalink']; ?>"
                           class="a-none pl-5"><?php echo date('m-d', $created); ?>  <?php echo $post['title']; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </div>
</div>

<?php $this->need('base/footer.php'); ?>

