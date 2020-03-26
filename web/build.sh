#!/bin/bash

PACKAGE=""

function usage(){
    echo "使用方法: ./build.sh -T dev|beta|gray|prod [other args]..."
}

function notify(){
    echo "__BUILD_PACKAGE__: $PACKAGE" >&2
}

#######################################################################
#  自定义脚本
#######################################################################
function compile_source(){
    local env=$(echo $tag | sed 's/[0-9]//g')
    local group=$(echo $tag | sed 's/[^0-9]//g')
    local key='dev'
    case $env in
        prod|gray)
            key="prod"
            ;;
        *)
            key="$env"
            ;;
    esac
    echo "$key" >.envkey
}
function build(){
    echo "Tag: $tag"
    echo "============================="

    TARGET='target'
    FILENAME='happy.zip'

    echo "准备环境.."
    if [[ ! -d "$TARGET" ]]; then
        mkdir -p "$TARGET"
    fi
    package="$TARGET/$FILENAME"

    # compile source
    echo "编译源码.."
    compile_source

    echo "打包.."
    zip -r $package . -x=".git/*" -x="target/*" -x="tests/*" -x="swoole.ini" -x="server/*" -x="build.sh" >/dev/null
    if [[ $? == 0 ]]; then
        PACKAGE=$package
        echo "构建完成"
        return 0
    fi
  
    echo "构建失败"
    return $?
}
#######################################################################


#### 参数解析 #####
key=''
value=''
for it in "$@" ; do
    if [[ $it == '-T' ]]; then
        key='tag'
    elif [[ $it == --* ]]; then
        key=${it##*--}
    elif [[ $it == -* ]]; then
        key=${it##*-}
    else
        value="$it"
        eval "$key='$value'"
        key=''
        value=''
    fi
done

if [[ ! $tag =~ ^(dev|beta|gray|prod) ]]; then
    usage
    exit 1
fi

build && notify
