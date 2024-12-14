export default function compose( ...funs ) {

    const props = funs.reduceRight( (total, current) => {
        return {...total, ...current}
    }, {})

    return function( source ) {
        return function( values ) {
            return source({ ...values, ...props })
        }
    }
}