import React from 'react';

export default class Game extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {

        return (
            <div className="game">
                <img src={this.props.image_url} alt={this.props.name} />

                <div className="info">

                    <h2>{this.props.name}</h2>

                    <p>{this.props.description}</p>

                    <ul>
                        {
                            this.props.genres.map(genre => {
                                return <li key={genre.id}>{genre.name}</li>
                            })
                        }
                    </ul>

                </div>
            </div>
        );
    }
}