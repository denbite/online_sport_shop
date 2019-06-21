import chai from 'chai';
import getOppositeVariation from '../../src/utils/getOppositeVariation';

const {expect} = chai;

describe('utils/getOppositeVariation', () => {
    it('should return correct values', () => {
        expect(getOppositeVariation('start')).to.equal('end');
        expect(getOppositeVariation('end')).to.equal('start');
        expect(getOppositeVariation('invalid')).to.equal('invalid');
    });
});
